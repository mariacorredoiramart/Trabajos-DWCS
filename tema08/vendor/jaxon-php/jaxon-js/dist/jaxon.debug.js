/*
    File: jaxon.debug.js
    
    This optional file contains the debugging module for use with jaxon.
    If you include this module after the standard <jaxon_core.js> module, you will receive debugging messages,
    including errors, that occur during the processing of your jaxon requests.
    
    Title: jaxon debugging module
    
    Please see <copyright.inc.php> for a detailed description, copyright and license information.
*/

/*
    @package jaxon
    @version $Id: jaxon.debug.js 327 2007-02-28 16:55:26Z calltoconstruct $
    @copyright Copyright (c) 2005-2007 by Jared White & J. Max Wilson
    @copyright Copyright (c) 2008-2009 by Joseph Woolley, Steffen Konerow, Jared White  & J. Max Wilson
    @license http://www.jaxonproject.org/bsd_license.txt BSD License
*/

try {
    /*
        Class: jaxon.debug

        This object contains the variables and functions used to display process state
        messages and to trap error conditions and report them to the user via
        a secondary browser window or alert messages as necessary.
    */
    if ('undefined' == typeof jaxon)
        throw { name: 'SequenceError', message: 'Error: Jaxon core was not detected, debug module disabled.' }

    if ('undefined' == typeof jaxon.debug)
        jaxon.debug = {}

} catch (e) {
    alert(e.name + ': ' + e.message);
}

(function(self, parameters, request, response, handler, utils) {
    /*
        String: jaxon.debug.workId
        
        Stores a 'unique' identifier for this session so that an existing debugging
        window can be detected, else one will be created.
    */
    const workId = 'jaxonWork' + new Date().getTime();

    /*
        String: jaxon.debug.windowSource
        
        The default URL that is given to the debugging window upon creation.
    */
    self.windowSource = 'about:blank';

    /*
        String: jaxon.debug.windowID
        
        A 'unique' name used to identify the debugging window that is attached
        to this jaxon session.
    */
    self.windowID = 'jaxon_debug_' + workId;

    /*
        String: windowStyle
        
        The parameters that will be used to create the debugging window.
    */
    self.windowStyle = 'width=800,height=600,scrollbars=yes,resizable=yes,status=yes';

    /*
        String: windowTemplate
        
        The HTML template and CSS style information used to populate the
        debugging window upon creation.
    */
    self.windowTemplate =
        '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' +
        '<html><head>' +
        '<title>jaxon debug output</title>' +
        '<style type="text/css">' +
        '/* <![CDATA[ */' +
        '.debugEntry { margin: 3px; padding: 3px; border-top: 1px solid #999999; } ' +
        '.debugDate { font-weight: bold; margin: 2px; } ' +
        '.debugText { margin: 2px; } ' +
        '.warningText { margin: 2px; font-weight: bold; } ' +
        '.errorText { margin: 2px; font-weight: bold; color: #ff7777; }' +
        '/* ]]> */' +
        '</style>' +
        '</head><body>' +
        '<h2>jaxon debug output</h2>' +
        '<div id="debugTag"></div>' +
        '</body></html>';

    /*
        Boolean: jaxon.debug.isLoaded
        
        true - indicates that the debugging module is loaded
    */
    self.isLoaded = true;

    /*
        Boolean: isLoaded
        
        true - indicates that the verbose debugging module is loaded.
    */
    self.verbose.isLoaded = false;

    /*
        Boolean: active
        
        true - indicates that the verbose debugging module is active.
    */
    self.verbose.active = false;

    /*
        Function: jaxon.debug.getExceptionText
        
        Parameters:
        e - (object): Exception
    */
    const getExceptionText = function(e) {
        if ('undefined' != typeof e.code) {
            if ('undefined' != typeof self.exceptions[e.code]) {
                const msg = self.exceptions[e.code];
                if ('undefined' != typeof e.data) {
                    msg.replace('{data}', e.data);
                }
                return msg;
            }
        } else if ('undefined' != typeof e.name) {
            const msg = 'undefined' != typeof e.message ? e.name + ': ' + e.message : e.name;
            return msg;
        }
        return 'An unknown error has occurred.';
    }

    /*
        Function: jaxon.debug.prepareDebugText
        
        Convert special characters to their HTML equivellents so they will show up in the <jaxon.debug.window>.
        
        Parameters:
            text - (string): Debug text
    */
    self.prepareDebugText = function(text) {
        try {
            text = text.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\n/g, '<br />');
            return text;
        } catch (e) {
            const stringReplace = function(haystack, needle, newNeedle) {
                const segments = haystack.split(needle);
                haystack = '';
                for (let i = 0; i < segments.length; ++i) {
                    if (0 != i)
                        haystack += newNeedle;
                    haystack += segments[i];
                }
                return haystack;
            }
            self.prepareDebugText = function(text) {
                text = stringReplace(text, '&', '&amp;');
                text = stringReplace(text, '<', '&lt;');
                text = stringReplace(text, '>', '&gt;');
                text = stringReplace(text, '\n', '<br />');
                return text;
            }
            self.prepareDebugText(text);
        }
    }

    /*
        Function: jaxon.debug.writeDebugMessage
        
        Output a debug message to the debug window if available or send to an
        alert box.  If the debug window has not been created, attempt to 
        create it.
        
        Parameters:
        
        text - (string):  The text to output.
        
        prefix - (string):  The prefix to use; this is prepended onto the 
            message; it should indicate the type of message (warning, error)
            
        cls - (string):  The className that will be applied to the message;
            invoking a style from the CSS provided in  <self.windowTemplate>.
            Should be one of the following:
            - warningText
            - errorText
    */
    const writeDebugMessage = function(text, prefix, cls) {
        try {
            if (!self.window || self.window.closed) {
                self.window = window.open(self.windowSource, self.windowID, self.windowStyle);
                if ("about:blank" == self.windowSource)
                    self.window.document.write(self.windowTemplate);
            }
            const xdw = self.window;
            const xdwd = xdw.document;
            if ('undefined' == typeof prefix)
                prefix = '';
            if ('undefined' == typeof cls)
                cls = 'debugText';

            text = self.prepareDebugText(text);

            const debugTag = xdwd.getElementById('debugTag');
            const debugEntry = xdwd.createElement('div');
            const debugDate = xdwd.createElement('span');
            const debugText = xdwd.createElement('pre');

            debugDate.innerHTML = new Date().toString();
            debugText.innerHTML = prefix + text;

            debugEntry.appendChild(debugDate);
            debugEntry.appendChild(debugText);
            debugTag.insertBefore(debugEntry, debugTag.firstChild);
            // don't allow 'style' issues to hinder the debug output
            try {
                debugEntry.className = 'debugEntry';
                debugDate.className = 'debugDate';
                debugText.className = cls;
            } catch (e) {}
        } catch (e) {
            if (text.length > 1500) {
                text = text.substr(0, 1500) + ' ...\n(Truncated)';
            }
            alert(self.messages.heading + text);
        }
    }

    /*
        Function: jaxon.ajax.handler.unregister
        
        Catch any exception thrown during the unregistration of command handler and display an appropriate debug message.
        
        This is a wrapper around the standard <jaxon.ajax.handler.unregister> function.
        
        Parameters:
            child - (object): Childnode
            obj - (object): Object
            
    */
    const commandHandler = handler.unregister('dbg');
    handler.register('dbg', function(args) {
        args.cmdFullName = 'debug message';
        writeDebugMessage(args.data, self.messages.warning, 'warningText');
        return commandHandler(args);
    });

    /*
        Function: jaxon.debug.executeCommand
        
        Catch any exceptions that are thrown by a response command handler
        and display a message in the debugger.
        
        This is a wrapper function which surrounds the standard 
        <jaxon.ajax.handler.execute> function.
    */
    const executeCommand = handler.execute;
    handler.execute = function(args) {
        try {
            if ('undefined' == typeof args.cmd)
                throw { code: 10006 };
            if (false == handler.isRegistered(args))
                throw { code: 10007, data: args.cmd };
            return executeCommand(args);
        } catch (e) {
            let msg = 'jaxon.ajax.handler.execute (';
            if ('undefined' != typeof args.sequence) {
                msg += '#' + args.sequence + ', ';
            }
            if ('undefined' != typeof args.cmdFullName) {
                msg += '"' + args.cmdFullName + '"';
            }
            msg += '):\n' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
        }
        return true;
    }

    /*
        Function: jaxon.utils.dom.$
        
        Catch any exceptions thrown while attempting to locate an HTML element by it's unique name.
        
        This is a wrapper around the standard <jaxon.utils.dom.$> function.
        
        Parameters:
        sId - (string): Element ID or name
        
    */
    const dom = utils.dom.$;
    utils.dom.$ = function(sId) {
        try {
            const returnValue = dom(sId);
            if ('object' != typeof returnValue)
                throw { code: 10008 };
            return returnValue;
        } catch (e) {
            const msg = '$:' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.warning, 'warningText');
        }
    }

    /*
        Function: jaxon.ajax.request._send
        
        Generate a message indicating that the jaxon request is
        about the be sent to the server.
        
        This is a wrapper around the standard <jaxon.ajax.request._send> 
        function.
    */
    const sendRequest = request._send;
    request._send = function(oRequest) {
        try {
            const length = oRequest.requestData.length || 0;
            writeDebugMessage(self.messages.request.sending);
            oRequest.beginDate = new Date();
            sendRequest(oRequest);
            writeDebugMessage(self.messages.request.sent.supplant({ length }));
        } catch (e) {
            const msg = 'jaxon.ajax.request._send: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.request.submit
        
        Generate a message indicating that a request is ready to be 
        submitted; providing the URL and the function being invoked.
        
        Catch any exceptions thrown and display a message.
        
        This is a wrapper around the standard <jaxon.ajax.request.submit>
        function.
    */
    const submitRequest = request.submit;
    request.submit = function(oRequest) {
        let msg = oRequest.method + ': ' + oRequest.URI + '\n';
        let text = decodeURIComponent(oRequest.requestData);
        text = text.replace(new RegExp('&jxn', 'g'), '\n&jxn');
        /*text = text.replace(new RegExp('<jxnobj>', 'g'), '\n<jxnobj>');
        text = text.replace(new RegExp('<e>', 'g'), '\n<e>');
        text = text.replace(new RegExp('</jxnobj>', 'g'), '\n</jxnobj>\n');*/
        msg += text;
        writeDebugMessage(msg);

        msg = self.messages.request.calling;
        const separator = '\n';
        for (let mbr in oRequest.functionName) {
            msg += separator + mbr + ': ' + oRequest.functionName[mbr];
        }
        /*msg += separator;
        msg += self.messages.request.uri;
        msg += separator;
        msg += oRequest.URI;*/
        writeDebugMessage(msg);

        try {
            return submitRequest(oRequest);
        } catch (e) {
            writeDebugMessage(e.message);
            if (0 < oRequest.retry)
                throw e;
        }
    }

    /*
        Function: jaxon.ajax.request.initialize
        
        Generate a message indicating that the request object is
        being initialized.
        
        This is a wrapper around the standard <jaxon.ajax.request.initialize>
        function.
    */
    const initializeRequest = request.initialize;
    request.initialize = function(oRequest) {
        try {
            const msg = self.messages.request.init;
            writeDebugMessage(msg);
            return initializeRequest(oRequest);
        } catch (e) {
            const msg = 'jaxon.ajax.request.initialize: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.parameters.process
        
        Generate a message indicating that the request object is
        being populated with the parameters provided.
        
        This is a wrapper around the standard <jaxon.ajax.parameters.process>
        function.
    */
    const processParameters = parameters.process;
    parameters.process = function(oRequest) {
        try {
            if ('undefined' != typeof oRequest.parameters) {
                const msg = self.messages.processing.parameters.supplant({
                    count: oRequest.parameters.length
                });
                writeDebugMessage(msg);
            } else {
                const msg = self.messages.processing.no_parameters;
                writeDebugMessage(msg);
            }
            return processParameters(oRequest);
        } catch (e) {
            const msg = 'jaxon.ajax.parameters.process: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.request.prepare
        
        Generate a message indicating that the request is being
        prepared.  This may occur more than once for a request
        if it errors and a retry is attempted.
        
        This is a wrapper around the standard <jaxon.ajax.request.prepare>
    */
    const prepareRequest = request.prepare;
    request.prepare = function(oRequest) {
        try {
            const msg = self.messages.request.preparing;
            writeDebugMessage(msg);
            return prepareRequest(oRequest);
        } catch (e) {
            const msg = 'jaxon.ajax.request.prepare: '; + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.handler.call
        
        Validates that a function name was provided, generates a message 
        indicating that a jaxon call is starting and sets a flag in the
        request object indicating that debugging is enabled for this call.
        
        This is a wrapper around the standard <jaxon.ajax.handler.call> function.
    */
    const callHandler = handler.call;
    handler.call = function() {
        try {
            const numArgs = arguments.length;

            if (0 == numArgs)
                throw { code: 10009 };

            const command = arguments[0];
            const rv = callHandler(command);

            writeDebugMessage(self.messages.processing.calling.supplant({
                cmd: command.fullName || command.cmd,
                options: JSON.stringify({
                    ...(command.id ? { id: command.id } : {}),
                    prop: command.prop,
                    data: command.data,
                }, null, 2),
            }));

            return rv;
        } catch (e) {
            const msg = 'jaxon.ajax.handler.call: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.request.execute
        
        Validates that a function name was provided, generates a message 
        indicating that a jaxon request is starting and sets a flag in the
        request object indicating that debugging is enabled for this request.
        
        This is a wrapper around the standard <jaxon.ajax.request.execute> function.
    */
    const executeRequest = request.execute;
    request.execute = function() {
        try {
            writeDebugMessage(self.messages.request.starting);

            const numArgs = arguments.length;

            if (0 == numArgs)
                throw { code: 10010 };

            const oFunction = arguments[0];
            const oOptions = 1 < numArgs ? arguments[1] : {};
            oOptions.debugging = true;

            return executeRequest(oFunction, oOptions);
        } catch (e) {
            const msg = 'jaxon.ajax.request.execute: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.ajax.response.received
        
        Generate a message indicating that a response has been received
        from the server; provide some statistical data regarding the
        response and the response time.
        
        Catch any exceptions that are thrown during the processing of
        the response and generate a message.
        
        This is a wrapper around the standard <jaxon.ajax.response.received>
        function.
    */
    const responseReceived = response.received;
    response.received = function(oRequest) {
        try {
            const status = oRequest.response.status;
            if (response.isSuccessCode(status)) {
                oRequest.midDate = new Date();
                const msg = self.messages.response.success.supplant({
                    status: status,
                    length: JSON.stringify(oRequest.responseContent).length,
                    duration: oRequest.midDate - oRequest.beginDate
                }) + '\n' + JSON.stringify(oRequest.responseContent, null, 2);
                writeDebugMessage(msg);
            } else if (response.isErrorCode(status)) {
                const msg = self.messages.response.content.supplant({
                    status: status,
                    text: JSON.stringify(oRequest.responseContent, null, 2)
                });
                writeDebugMessage(msg, self.messages.error, 'errorText');
            } else if (response.isRedirectCode(status)) {
                const msg = self.messages.response.redirect.supplant({
                    location: oRequest.response.headers.get('location')
                });
                writeDebugMessage(msg);
            }
            return responseReceived(oRequest);
        } catch (e) {
            const msg = 'jaxon.ajax.response.received: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
        }

        return null;
    }

    /*
        Function: jaxon.ajax.request.complete
        
        Generate a message indicating that the request has completed
        and provide some statistics regarding the request and response.
        
        This is a wrapper around the standard <jaxon.ajax.request.complete>
        function.
    */
    const requestCompleted = request.complete;
    request.complete = function(oRequest) {
        try {
            const returnValue = requestCompleted(oRequest);
            oRequest.endDate = new Date();
            const duration = (oRequest.endDate - oRequest.beginDate);
            const msg = self.messages.processing.done.supplant({ duration });
            writeDebugMessage(msg);
            return returnValue;
        } catch (e) {
            const msg = 'jaxon.ajax.request.complete: ' + getExceptionText(e) + '\n';
            writeDebugMessage(msg, self.messages.error, 'errorText');
            throw e;
        }
    }

    /*
        Function: jaxon.cmd.body.assign
        
        Catch any exceptions thrown during the assignment and display an error message.
        
        This is a wrapper around the standard <jaxon.cmd.body.assign> function.
    */
    if (jaxon.cmd.body.assign) {
        const assign = jaxon.cmd.body.assign;
        jaxon.cmd.body.assign = function({ target: element, prop: property, data }) {
            try {
                return assign(element, property, data);
            } catch (e) {
                const msg = 'jaxon.cmd.body.assign: ' + getExceptionText(e) + '\n' +
                    'Eval: element.' + property + ' = data;\n';
                writeDebugMessage(msg, self.messages.error, 'errorText');
            }
            return true;
        }
    }
})(jaxon.debug, jaxon.ajax.parameters, jaxon.ajax.request, jaxon.ajax.response,
    jaxon.ajax.handler, jaxon.utils);

/*
    The jaxon verbose debugging module.
    This is an optional module, include in your project with care. :)
*/
jaxon.dom.ready(function() {
    // Generate wrapper functions for verbose debug.
    (function(self) {
        if (!self.active) {
            return;
        }

        /*
            Function: jaxon.debug.verbose.expandObject
            
            Generate a debug message expanding all the first level
            members found therein.
            
            
            Parameters:
            
            obj - (object):  The object to be enumerated.
            
            Returns:
            
            string - The textual representation of all the first
                level members.
        */
        const expandObject = function(obj) {
            const rec = 1 < arguments.length ? arguments[1] : true;
            if ('function' == typeof(obj)) {
                return '[Function]';
            } else if ('object' == typeof(obj)) {
                if (true == rec) {
                    let t = ' { ';
                    let separator = '';
                    for (let m in obj) {
                        t += separator;
                        t += m;
                        t += ': ';
                        try {
                            t += expandObject(obj[m], false);
                        } catch (e) {
                            t += '[n/a]';
                        }
                        separator = ', ';
                    }
                    t += ' } ';
                    return t;
                } else return '[Object]';
            } else return '"' + obj + '"';
        }

        /*
            Function: jaxon.debug.verbose.makeFunction
            
            Generate a wrapper function around the specified function.
            
            Parameters:
            
            obj - (object):  The object that contains the function to be
                wrapped.
            name - (string):  The name of the function to be wrapped.
            
            Returns:
            
            function - The wrapper function.
        */
        const makeFunction = function(obj, name) {
            return function() {
                let fun = name + '(';

                let separator = '';
                const pLen = arguments.length;
                for (let p = 0; p < pLen; ++p) {
                    fun += separator;
                    fun += expandObject(arguments[p]);
                    separator = ',';
                }

                fun += ');';

                let msg = '--> ' + fun;

                writeDebugMessage(msg);

                const returnValue = true;
                let code = 'returnValue = obj(';
                separator = '';
                for (let p = 0; p < pLen; ++p) {
                    code += separator + 'arguments[' + p + ']';
                    separator = ',';
                }
                code += ');';

                eval(code);

                msg = '<-- ' + fun + ' returns ' + expandObject(returnValue);
                writeDebugMessage(msg);

                return returnValue;
            }
        }

        /*
            Function: jaxon.debug.verbose.hook
            
            Generate a wrapper function around each of the functions contained within the specified object.
            
            Parameters: 
            
            x - (object):  The object to be scanned.
            base - (string):  The base reference to be prepended to the generated wrapper functions.
        */
        self.hook = function(x, base) {
            for (let m in x) {
                if ('function' === typeof(x[m])) {
                    x[m] = makeFunction(x[m], base + m);
                }
            }
        }

        self.hook(jaxon, 'jaxon.');
        self.hook(jaxon.cmd.body, 'jaxon.cmd.body.');
        self.hook(jaxon.cmd.event, 'jaxon.cmd.event.');
        self.hook(jaxon.cmd.form, 'jaxon.cmd.form.');
        self.hook(jaxon.cmd.head, 'jaxon.cmd.head.');
        self.hook(jaxon.cmd.script, 'jaxon.cmd.script.');
        self.hook(jaxon.utils.dom, 'jaxon.utils.dom.');
        self.hook(jaxon.utils.string, 'jaxon.utils.string.');
        self.hook(jaxon.utils.queue, 'jaxon.utils.queue.');
        self.hook(jaxon.utils.upload, 'jaxon.utils.upload.');
        self.hook(jaxon.ajax.callback, 'jaxon.ajax.callback.');
        self.hook(jaxon.ajax.handler, 'jaxon.ajax.handler.');

        self.isLoaded = true;
    })(jaxon.debug.verbose);
});
