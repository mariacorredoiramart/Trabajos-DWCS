/*
    @package jaxon
    @version $Id: jaxon.core.js 327 2007-02-28 16:55:26Z calltoconstruct $
    @copyright Copyright (c) 2005-2007 by Jared White & J. Max Wilson
    @copyright Copyright (c) 2008-2010 by Joseph Woolley, Steffen Konerow, Jared White  & J. Max Wilson
    @copyright Copyright (c) 2017 by Thierry Feuzeu, Joseph Woolley, Steffen Konerow, Jared White  & J. Max Wilson
    @license https://opensource.org/license/bsd-3-clause/ BSD License
*/

/**
 * Class: jaxon
 */
var jaxon = {
    /**
     * Version number
     */
    version: {
        major: '4',
        minor: '0',
        patch: '2',
    },

    debug: {
        /**
         * Class: jaxon.debug.verbose
         *
         * Provide a high level of detail which can be used to debug hard to find problems.
         */
        verbose: {},
    },

    ajax: {
        callback: {},
        handler: {},
        parameters: {},
        request: {},
        response: {},
    },

    cmd: {
        head: {},
        body: {},
        script: {},
        form: {},
        event: {},
    },

    utils: {
        dom: {},
        form: {},
        queue: {},
        string: {},
        upload: {},
    },

    dom: {},

    /**
     * This class contains all the default configuration settings.
     * These are application level settings; however, they can be overridden by including
     * a jaxon.config definition prior to including the <jaxon_core.js> file, or by
     * specifying the appropriate configuration options on a per call basis.
     */
    config: {
        /**
         * An array of header entries where the array key is the header option name and
         * the associated value is the value that will set when the request object is initialized.
         *
         * These headers will be set for both POST and GET requests.
         */
        commonHeaders: {
            'If-Modified-Since': 'Sat, 1 Jan 2000 00:00:00 GMT'
        },

        /**
         * An array of header entries where the array key is the header option name and the
         * associated value is the value that will set when the request object is initialized.
         */
        postHeaders: {},

        /**
         * An array of header entries where the array key is the header option name and the
         * associated value is the value that will set when the request object is initialized.
         */
        getHeaders: {},

        /**
         * true if jaxon should display a wait cursor when making a request, false otherwise.
         */
        waitCursor: false,

        /**
         * true if jaxon should log the status to the console during a request, false otherwise.
         */
        statusMessages: false,

        /**
         * The base document that will be used throughout the code for locating elements by ID.
         */
        baseDocument: document,

        /**
         * The URI that requests will be sent to.
         *
         * @var {string}
         */
        requestURI: document.URL,

        /**
         * The request mode.
         * - 'asynchronous' - The request will immediately return, the response will be processed
         *   when (and if) it is received.
         * - 'synchronous' - The request will block, waiting for the response.
         *   This option allows the server to return a value directly to the caller.
         */
        defaultMode: 'asynchronous',

        /**
         * The Hyper Text Transport Protocol version designated in the header of the request.
         */
        defaultHttpVersion: 'HTTP/1.1',

        /**
         * The content type designated in the header of the request.
         */
        defaultContentType: 'application/x-www-form-urlencoded',

        /**
         * The delay time, in milliseconds, associated with the <jaxon.callback.onRequestDelay> event.
         */
        defaultResponseDelayTime: 1000,

        /**
         * Always convert the reponse content to json.
         */
        convertResponseToJson: true,

        /**
         * The amount of time to wait, in milliseconds, before a request is considered expired.
         * This is used to trigger the <jaxon.callback.onExpiration event.
         */
        defaultExpirationTime: 10000,

        /**
         * The method used to send requests to the server.
         * - 'POST': Generate a form POST request
         * - 'GET': Generate a GET request; parameters are appended to <jaxon.config.requestURI> to form a URL.
         */
        defaultMethod: 'POST', // W3C: Method is case sensitive

        /**
         * The number of times a request should be retried if it expires.
         */
        defaultRetry: 5,

        /**
         * The value returned by <jaxon.request> when in asynchronous mode, or when a syncrhonous call
         * does not specify the return value.
         */
        defaultReturnValue: false,

        /**
         * The maximum depth of recursion allowed when serializing objects to be sent to the server in a request.
         */
        maxObjectDepth: 20,

        /**
         * The maximum number of members allowed when serializing objects to be sent to the server in a request.
         */
        maxObjectSize: 2000,

        /**
         * The maximum number of commands allowed in a single response.
         */
        commandQueueSize: 1000,

        /**
         * The maximum number of requests that can be processed simultaneously.
         */
        requestQueueSize: 1000,

        /**
         * Common options for all HTTP requests to the server.
         */
        httpRequestOptions: {
            mode: "cors", // no-cors, *cors, same-origin
            cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
            credentials: "same-origin", // include, *same-origin, omit
            redirect: "manual", // manual, *follow, error
        },
    },
};

/**
 * Register the command handlers provided by the library.
 */
(function(cfg) {
    /**
     * Set the options in the request object
     *
     * @param {object} oRequest The request context object.
     *
     * @returns {void}
     */
    cfg.setRequestOptions = (oRequest) => {
        if (cfg.requestURI === undefined) {
            throw { code: 10005 };
        }

        const aHeaders = ['commonHeaders', 'postHeaders', 'getHeaders'];
        aHeaders.forEach(sHeader => oRequest[sHeader] = { ...cfg[sHeader], ...oRequest[sHeader] });

        const oDefaultOptions = {
            statusMessages: cfg.statusMessages,
            waitCursor: cfg.waitCursor,
            mode: cfg.defaultMode,
            method: cfg.defaultMethod,
            URI: cfg.requestURI,
            httpVersion: cfg.defaultHttpVersion,
            contentType: cfg.defaultContentType,
            convertResponseToJson: cfg.convertResponseToJson,
            retry: cfg.defaultRetry,
            returnValue: cfg.defaultReturnValue,
            maxObjectDepth: cfg.maxObjectDepth,
            maxObjectSize: cfg.maxObjectSize,
            context: window,
            upload: false,
            aborted: false,
        };
        Object.keys(oDefaultOptions).forEach(sOption =>
            oRequest[sOption] = oRequest[sOption] ?? oDefaultOptions[sOption]);

        oRequest.method = oRequest.method.toUpperCase();
        if (oRequest.method !== 'GET') {
            oRequest.method = 'POST'; // W3C: Method is case sensitive
        }
        oRequest.requestRetry = oRequest.retry;
    };

    /**
     * Class: jaxon.config.status
     *
     * Provides support for updating the browser's status bar during the request process.
     * By splitting the status bar functionality into an object, the jaxon developer has the opportunity
     * to customize the status bar messages prior to sending jaxon requests.
     */
    cfg.status = {
        /**
         * A set of event handlers that will be called by the
         * jaxon framework to set the status bar messages.
         *
         * @type {object}
         */
        update: {
            onRequest: () => console.log('Sending Request...'),
            onWaiting: () => console.log('Waiting for Response...'),
            onProcessing: () => console.log('Processing...'),
            onComplete: () => console.log('Done.'),
        },

        /**
         * A set of event handlers that will be called by the
         * jaxon framework where status bar updates would normally occur.
         *
         * @type {object}
         */
        dontUpdate: {
            onRequest: () => {},
            onWaiting: () => {},
            onProcessing: () => {},
            onComplete: () => {}
        },
    };

    /**
     * Class: jaxon.config.cursor
     *
     * Provides the base functionality for updating the browser's cursor during requests.
     * By splitting this functionality into an object of it's own, jaxon developers can now
     * customize the functionality prior to submitting requests.
     */
    cfg.cursor = {
        /**
         * Constructs and returns a set of event handlers that will be called by the
         * jaxon framework to effect the status of the cursor during requests.
         *
         * @type {object}
         */
        update: {
            onWaiting: () => {
                if (jaxon.config.baseDocument.body) {
                    jaxon.config.baseDocument.body.style.cursor = 'wait';
                }
            },
            onComplete: () => {
                if (jaxon.config.baseDocument.body) {
                    jaxon.config.baseDocument.body.style.cursor = 'auto';
                }
            }
        },

        /**
         * Constructs and returns a set of event handlers that will be called by the jaxon framework
         * where cursor status changes would typically be made during the handling of requests.
         *
         * @type {object}
         */
        dontUpdate: {
            onWaiting: () => {},
            onComplete: () => {}
        },
    };
})(jaxon.config);


/**
 * Class: jaxon.utils.dom
 */

(function(self, baseDocument) {
    /**
     * Shorthand for finding a uniquely named element within the document.
     *
     * @param {string} sId - The unique name of the element (specified by the ID attribute)
     *
     * @returns {object} The element found or null.
     *
     * @see <self.$>
     */
    self.$ = (sId) => !sId ? null :
        (typeof sId === 'string' ? baseDocument.getElementById(sId) : sId);

    /**
     * Create a div as workspace for the getBrowserHTML() function.
     *
     * @returns {object} The workspace DOM element.
     */
    const _getWorkspace = () => {
        const elWorkspace = self.$('jaxon_temp_workspace');
        if (elWorkspace) {
            return elWorkspace;
        }
        // Workspace not found. Must be created.
        if (!baseDocument.body) {
            return null;
        }

        const elNewWorkspace = baseDocument.createElement('div');
        elNewWorkspace.setAttribute('id', 'jaxon_temp_workspace');
        elNewWorkspace.style.display = 'none';
        elNewWorkspace.style.visibility = 'hidden';
        baseDocument.body.appendChild(elNewWorkspace);
        return elNewWorkspace;
    };

    /**
     * Insert the specified string of HTML into the document, then extract it.
     * This gives the browser the ability to validate the code and to apply any transformations it deems appropriate.
     *
     * @param {string} sValue A block of html code or text to be inserted into the browser's document.
     *
     * @returns {string} The (potentially modified) html code or text.
     */
    self.getBrowserHTML = (sValue) => {
        const elWorkspace = _getWorkspace();
        elWorkspace.innerHTML = sValue;
        const browserHTML = elWorkspace.innerHTML;
        elWorkspace.innerHTML = '';
        return browserHTML;
    };

    /**
     * Tests to see if the specified data is the same as the current value of the element's attribute.
     *
     * @param {string|object} element The element or it's unique name (specified by the ID attribute)
     * @param {string} attribute The name of the attribute.
     * @param {string} newData The value to be compared with the current value of the specified element.
     *
     * @returns {true} The specified value differs from the current attribute value.
     * @returns {false} The specified value is the same as the current value.
     */
    self.willChange = (element, attribute, newData) => {
        element = self.$(element);
        return !element ? false : (newData != element[attribute]);
    };

    /**
     * Tests to see if the specified data is the same as the current value of the element's attribute.
     *
     * @param {string|object} element The element or it's unique name (specified by the ID attribute)
     *
     * @returns {void}
     */
    self.removeElement = (element) => {
        element = self.$(element);
        if (element && element.parentNode && element.parentNode.removeChild) {
            element.parentNode.removeChild(element);
        }
    };

    /**
     * Get the value of an attribute of an object.
     * Can also get the value of a var in an array.
     *
     * @param {object} xElement The object with the attribute.
     * @param {string} sAttrName The attribute name.
     *
     * @returns {mixed}
     */
    self.getAttrValue = (xElement, sAttrName) => {
        if((aMatches = sAttrName.match(/^(.+)\[(\d+)\]$/)) === null)
        {
            return xElement[sAttrName];
        }

        // The attribute is an array in the form "var[indice]".
        sAttrName = aMatches[1];
        const nAttrIndice = parseInt(aMatches[2]);
        return xElement[sAttrName][nAttrIndice];
    }

    /**
     * Find a function using its name as a string.
     *
     * @param {string} sFuncName The name of the function to find.
     * @param {object} context
     *
     * @returns {object|null}
     */
    self.findFunction = (sFuncName, context = window) => {
        const aNames = sFuncName.split(".");
        const nLength = aNames.length;
        for (let i = 0; i < nLength && (context); i++) {
            context = self.getAttrValue(context, aNames[i]);
        }
        return context ?? null;
    };

    /**
     * Given an element and an attribute with 0 or more dots,
     * get the inner object and the corresponding attribute name.
     *
     * @param {string} sAttrName The attribute name.
     * @param {object=} xElement The outer element.
     *
     * @returns {object|null} The inner object and the attribute name in an object.
     */
    self.getInnerObject = (sAttrName, xElement = window) => {
        const aNames = sAttrName.split('.');
        // Get the last element in the array.
        sAttrName = aNames.pop();
        // Move to the inner object.
        const nLength = aNames.length;
        for (let i = 0; i < nLength && (xElement); i++) {
            // The real name for the "css" object is "style".
            const sRealAttrName = aNames[i] === 'css' ? 'style' : aNames[i];
            xElement = self.getAttrValue(xElement, sRealAttrName);
        }
        return !xElement ? null : { node: xElement, attr: sAttrName };
    };

    /**
     * Create a function by inserting its code in the page using a <script> tag.
     *
     * @param {string} funcCode
     * @param {string='jaxon.cmd.script.context.delegateCall'} funcName
     * 
     * @returns {boolean}
     */
    self.createFunction = (funcCode, funcName = 'jaxon.cmd.script.context.delegateCall') => {
        if (!funcCode) {
            return false;
        }

        try {
            const scriptTagId = 'jaxon_cmd_script_' + (funcName === undefined ?
                'delegate_call' : funcName.toLowerCase().replaceAll('.', '_'));

            // Remove the tag if it already exists.
            self.removeElement(scriptTagId);
            // Create a new tag.
            const scriptTag = baseDocument.createElement('script');
            scriptTag.setAttribute('id', scriptTagId);
            scriptTag.textContent = `
    ${funcName} = ${funcCode}
`;
            baseDocument.body.appendChild(scriptTag);
        } catch (e) {
            return false;
        }

        return true;
    };
})(jaxon.utils.dom, jaxon.config.baseDocument);


/**
 * Class: jaxon.utils.form
 */

(function(self, dom) {
    /**
     * @param {object} xOptions
     * @param {object} child
     * @param {string} child.type
     * @param {string} child.name
     * @param {string} child.tagName
     * @param {boolean} child.checked
     * @param {boolean} child.disabled
     * @param {mixed} child.value
     * @param {array} child.options
     *
     * @returns {void}
     */
    const _getValue = (xOptions, { type, name, tagName, checked, disabled, value, options }) => {
        if (!name || 'PARAM' === tagName)
            return;
        if (!xOptions.submitDisabled && disabled)
            return;
        const { prefix } = xOptions;
        if (prefix.length > 0 && prefix !== name.substring(0, prefix.length))
            return;
        if ((type === 'radio' || type === 'checkbox') && !checked)
            return;
        if (type === 'file')
            return;

        const values = type !== 'select-multiple' ? value :
            Array.from(options).filter(({ selected }) => selected).map(({ value: v }) => v);
        const keyBegin = name.indexOf('[');

        if (keyBegin < 0) {
            xOptions.values[name] = values;
            return;
        }

        // Parse names into brackets
        let k = name.substring(0, keyBegin);
        let a = name.substring(keyBegin);
        if (xOptions.values[k] === undefined) {
            xOptions.values[k] = {};
        }
        let p = xOptions.values; // pointer reset
        while (a.length > 0) {
            const sa = a.substring(0, a.indexOf(']') + 1);
            const lastKey = k; //save last key
            const lastRef = p; //save last pointer

            a = a.substring(a.indexOf(']') + 1);
            p = p[k];
            k = sa.substring(1, sa.length - 1);
            if (k === '') {
                if ('select-multiple' === type) {
                    k = lastKey; //restore last key
                    p = lastRef;
                } else {
                    k = p.length;
                }
            }
            if (k === undefined) {
                /*check against the global xOptions.values Stack wich is the next(last) usable index */
                k = Object.keys(lastRef[lastKey]).length;
            }
            p[k] = p[k] || {};
        }
        p[k] = values;
    };

    /**
     * @param {object} xOptions
     * @param {array} children
     *
     * @returns {void}
     */
    const _getValues = (xOptions, children) => {
        children.forEach(child => {
            const { childNodes, type } = child;
            if (childNodes !== undefined && type !== 'select-one' && type !== 'select-multiple') {
                _getValues(xOptions, childNodes);
            }
           _getValue(xOptions, child);
        });
    };

    /**
     * Build an associative array of form elements and their values from the specified form.
     *
     * @param {string} formId The unique name (id) of the form to be processed.
     * @param {boolean=false} submitDisabled (optional): Include form elements which are currently disabled.
     * @param {string=''} prefix (optional): A prefix used for selecting form elements.
     *
     * @returns {object} An associative array of form element id and value.
     */
    self.getValues = (formId, submitDisabled = false, prefix = '') => {
        const xOptions = {
            // Submit disabled fields
            submitDisabled: (submitDisabled === true || submitDisabled === 1),
            // Only submit fields with a prefix
            prefix: prefix ?? '',
            // Form values
            values: {},
        };

        const form = dom.$(formId);
        if (form && form.childNodes) {
            _getValues(xOptions, form.childNodes);
        }
        return xOptions.values;
    };
})(jaxon.utils.form, jaxon.utils.dom);


/**
 * Class: jaxon.utils.queue
 */

(function(self) {
    /**
     * Construct and return a new queue object.
     *
     * @param {integer} size The number of entries the queue will be able to hold.
     *
     * @returns {object}
     */
    self.create = size => ({
        start: 0,
        count: 0,
        size: size,
        end: 0,
        elements: [],
        paused: false,
    });

    /**
     * Check id a queue is empty.
     *
     * @param {object} oQueue The queue to check.
     *
     * @returns {boolean}
     */
    self.empty = oQueue => oQueue.count <= 0;

    /**
     * Check id a queue is empty.
     *
     * @param {object} oQueue The queue to check.
     *
     * @returns {boolean}
     */
    self.full = oQueue => oQueue.count >= oQueue.size;

    /**
     * Push a new object into the tail of the buffer maintained by the specified queue object.
     *
     * @param {object} oQueue The queue in which you would like the object stored.
     * @param {object} obj    The object you would like stored in the queue.
     *
     * @returns {integer} The number of entries in the queue.
     */
    self.push = (oQueue, obj) => {
        // No push if the queue is full.
        if(self.full(oQueue)) {
            throw { code: 10003 };
        }

        oQueue.elements[oQueue.end] = obj;
        if(++oQueue.end >= oQueue.size) {
            oQueue.end = 0;
        }
        return ++oQueue.count;
    };

    /**
     * Push a new object into the head of the buffer maintained by the specified queue object.
     *
     * This effectively pushes an object to the front of the queue... it will be processed first.
     *
     * @param {object} oQueue The queue in which you would like the object stored.
     * @param {object} obj    The object you would like stored in the queue.
     *
     * @returns {integer} The number of entries in the queue.
     */
    self.pushFront = (oQueue, obj) => {
        // No push if the queue is full.
        if(self.full(oQueue)) {
            throw { code: 10003 };
        }

        // Simply push if the queue is empty
        if(self.empty(oQueue)) {
            return self.push(oQueue, obj);
        }

        // Put the object one position back.
        if(--oQueue.start < 0) {
            oQueue.start = oQueue.size - 1;
        }
        oQueue.elements[oQueue.start] = obj;
        return ++oQueue.count;
    };

    /**
     * Attempt to pop an object off the head of the queue.
     *
     * @param {object} oQueue The queue object you would like to modify.
     *
     * @returns {object|null}
     */
    self.pop = (oQueue) => {
        if(self.empty(oQueue)) {
            return null;
        }

        const obj = oQueue.elements[oQueue.start];
        delete oQueue.elements[oQueue.start];
        if(++oQueue.start >= oQueue.size) {
            oQueue.start = 0;
        }
        oQueue.count--;
        return obj;
    };

    /**
     * Attempt to pop an object off the head of the queue.
     *
     * @param {object} oQueue The queue object you would like to modify.
     *
     * @returns {object|null}
     */
    self.peek = (oQueue) => {
        if(self.empty(oQueue)) {
            return null;
        }
        return oQueue.elements[oQueue.start];
    };
})(jaxon.utils.queue);


/**
 * Class: jaxon.dom
 */

/**
 * Plain javascript replacement for jQuery's .ready() function.
 * See https://github.com/jfriend00/docReady for a detailed description, copyright and license information.
 */
(function(self) {
    "use strict";

    let readyList = [];
    let readyFired = false;
    let readyEventHandlersInstalled = false;

    /**
     * Call this when the document is ready.
     * This function protects itself against being called more than once
     */
    const ready = () => {
        if (readyFired) {
            return;
        }
        // this must be set to true before we start calling callbacks
        readyFired = true;
        // if a callback here happens to add new ready handlers,
        // the jaxon.dom.ready() function will see that it already fired
        // and will schedule the callback to run right after
        // this event loop finishes so all handlers will still execute
        // in order and no new ones will be added to the readyList
        // while we are processing the list
        readyList.forEach(cb => cb.fn.call(window, cb.ctx));
        // allow any closures held by these functions to free
        readyList = [];
    }

    const readyStateChange = () => document.readyState === "complete" && ready();

    /**
     * This is the one public interface
     * jaxon.dom.ready(fn, context);
     * The context argument is optional - if present, it will be passed as an argument to the callback
     */
    self.ready = function(callback, context) {
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() { callback(context); }, 1);
            return;
        }
        // add the function and context to the list
        readyList.push({ fn: callback, ctx: context });
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete" || (!document.attachEvent && document.readyState === "interactive")) {
            setTimeout(ready, 1);
            return;
        }
        if (!readyEventHandlersInstalled) {
            // first choice is DOMContentLoaded event
            document.addEventListener("DOMContentLoaded", ready, false);
            // backup is window load event
            window.addEventListener("load", ready, false);

            readyEventHandlersInstalled = true;
        }
    }
})(jaxon.dom);


/**
 * Class: jaxon.utils.string
 */

(function(self) {
    /**
     * Replace all occurances of the single quote character with a double quote character.
     *
     * @param {string=} haystack The source string to be scanned
     *
     * @returns {string|false} A new string with the modifications applied. False on error.
     */
    self.doubleQuotes = haystack => haystack === undefined ?
        false : haystack.replace(new RegExp("'", 'g'), '"');

    /**
     * Replace all occurances of the double quote character with a single quote character.
     *
     * @param {string=} haystack The source string to be scanned
     *
     * @returns {string|false} A new string with the modification applied
     */
    self.singleQuotes = haystack => haystack === undefined ?
        false : haystack.replace(new RegExp('"', 'g'), "'");

    /**
     * Detect, and if found, remove the prefix 'on' from the specified string.
     * This is used while working with event handlers.
     *
     * @param {string} sEventName The string to be modified
     *
     * @returns {string} The modified string
     */
    self.stripOnPrefix = (sEventName) => {
        sEventName = sEventName.toLowerCase();
        return sEventName.indexOf('on') === 0 ? sEventName.replace(/on/, '') : sEventName;
    };

    /**
     * Detect, and add if not found, the prefix 'on' from the specified string.
     * This is used while working with event handlers.
     *
     * @param {string} sEventName The string to be modified
     *
     * @returns {string} The modified string
     */
    self.addOnPrefix = (sEventName) => {
        sEventName = sEventName.toLowerCase();
        return sEventName.indexOf('on') !== 0 ? 'on' + sEventName : sEventName;
    };

    /**
     * Get the type of an object. Unlike typeof, this function distinguishes
     * objects from arrays, and the first letter is capitalized.
     *
     * @param {mixed} xObject The object to check
     *
     * @returns {string}
     */
    self.typeOf = (xObject) => Object.prototype.toString.call(xObject).slice(8, -1).toLowerCase();

    /**
     * String functions for Jaxon
     * See http://javascript.crockford.com/remedial.html for more explanation
     */
    if (!String.prototype.supplant) {
        /**
         * Substitute variables in the string
         *
         * @param {object} values The substitution values
         *
         * @returns {string}
         */
        String.prototype.supplant = function(values) {
            return this.replace(
                /\{([^{}]*)\}/g,
                (a, b) => {
                    const r = values[b];
                    const t = typeof r;
                    return t === 'string' || t === 'number' ? r : a;
                }
            );
        };
    }
})(jaxon.utils.string);


/**
 * Class: jaxon.utils.upload
 */

(function(self, dom, console) {
    /**
     * @param {object} oRequest A request object, created initially by a call to <jaxon.ajax.request.initialize>
     * @param {string=} oRequest.upload The HTML file upload field id
     *
     * @returns {boolean}
     */
    const initRequest = (oRequest) => {
        if (!oRequest.upload) {
            return false;
        }

        oRequest.upload = {
            id: oRequest.upload,
            input: null,
            form: null,
        };
        const input = dom.$(oRequest.upload.id);

        if (!input) {
            console.log('Unable to find input field for file upload with id ' + oRequest.upload.id);
            return false;
        }
        if (input.type !== 'file') {
            console.log('The upload input field with id ' + oRequest.upload.id + ' is not of type file');
            return false;
        }
        if (input.files.length === 0) {
            console.log('There is no file selected for upload in input field with id ' + oRequest.upload.id);
            return false;
        }
        if (input.name === undefined) {
            console.log('The upload input field with id ' + oRequest.upload.id + ' has no name attribute');
            return false;
        }
        oRequest.upload.input = input;
        oRequest.upload.form = input.form;
        return true;
    };

    /**
     * Check upload data and initialize the request.
     *
     * @param {object} oRequest A request object, created initially by a call to <jaxon.ajax.request.initialize>
     *
     * @returns {void}
     */
    self.initialize = (oRequest) => {
        // The content type shall not be set when uploading a file with FormData.
        // It will be set by the browser.
        if (!initRequest(oRequest)) {
            oRequest.postHeaders['content-type'] = oRequest.contentType;
        }
    }
})(jaxon.utils.upload, jaxon.utils.dom, console);


/**
 * Class: jaxon.ajax.callback
 */

(function(self, str, config) {
    /**
     * Create a timer to fire an event in the future.
     * This will be used fire the onRequestDelay and onExpiration events.
     *
     * @param {integer} iDelay The amount of time in milliseconds to delay.
     *
     * @returns {object} A callback timer object.
     */
    const setupTimer = (iDelay) => ({ timer: null, delay: iDelay });

    /**
     * The names of the available callbacks.
     *
     * @var {array}
     */
    const aCallbackNames = ['onInitialize', 'onProcessParams', 'onPrepare',
        'onRequest', 'onResponseDelay', 'onExpiration', 'beforeResponseProcessing',
        'onFailure', 'onRedirect', 'onSuccess', 'onComplete'];

    /**
     * Create a blank callback object.
     * Two optional arguments let you set the delay time for the onResponseDelay and onExpiration events.
     *
     * @param {integer=} responseDelayTime
     * @param {integer=} expirationTime
     *
     * @returns {object} The callback object.
     */
    self.create = (responseDelayTime, expirationTime) => {
        const oCallback = {
            timers: {
                onResponseDelay: setupTimer(responseDelayTime ?? config.defaultResponseDelayTime),
                onExpiration: setupTimer(expirationTime ?? config.defaultExpirationTime),
            },
        };
        aCallbackNames.forEach(sName => oCallback[sName] = null);
        return oCallback;
    };

    /**
     * The global callback object which is active for every request.
     *
     * @var {object}
     */
    self.callback = self.create();

    /**
     * Move all the callbacks defined directly in the oRequest object to the
     * oRequest.callback property, which may then be converted to an array.
     *
     * @param {object} oRequest
     *
     * @return {void}
     */
    self.initCallbacks = (oRequest) => {
        if (str.typeOf(oRequest.callback) === 'object') {
            oRequest.callback = [oRequest.callback];
        }
        if (str.typeOf(oRequest.callback) === 'array') {
            oRequest.callback.forEach(oCallback => {
                // Add the timers attribute, if it is not defined.
                if (oCallback.timers === undefined) {
                    oCallback.timers = {};
                }
            });
            return;
        }

        let callbackFound = false;
        // Check if any callback is defined in the request object by its own name.
        const callback = self.create();
        aCallbackNames.forEach(sName => {
            if (oRequest[sName] !== undefined) {
                callback[sName] = oRequest[sName];
                callbackFound = true;
                delete oRequest[sName];
            }
        });
        oRequest.callback = callbackFound ? [callback] : [];
    };

    /**
     * Get a flatten array of callbacks
     *
     * @param {object} oRequest The request context object.
     * @param {array=} oRequest.callback The request callback(s).
     *
     * @returns {array}
     */
    const getCallbacks = ({ callback = [] }) => [self.callback, ...callback];

    /**
     * Execute a callback event.
     *
     * @param {object} oCallback The callback object (or objects) which contain the event handlers to be executed.
     * @param {string} sFunction The name of the event to be triggered.
     * @param {object} xArgs The callback argument.
     *
     * @returns {void}
     */
    const execute = (oCallback, sFunction, xArgs) => {
        const func = oCallback[sFunction];
        if (!func || str.typeOf(func) !== 'function') {
            return;
        }
        const timer = oCallback.timers[sFunction];
        if (!timer) {
            func(xArgs); // Call the function directly.
            return;
        }
        // Call the function after the timeout.
        timer.timer = setTimeout(() => func(xArgs), timer.delay);
    };

    /**
     * Execute a callback event.
     *
     * @param {object} oRequest The request context object.
     * @param {string} sFunction The name of the event to be triggered.
     *
     * @returns {void}
     */
    self.execute = (oRequest, sFunction) => getCallbacks(oRequest)
        .forEach(oCallback => execute(oCallback, sFunction, oRequest));

    /**
     * Clear a callback timer for the specified function.
     *
     * @param {object} oCallback The callback object (or objects) that contain the specified function timer to be cleared.
     * @param {string} sFunction The name of the function associated with the timer to be cleared.
     *
     * @returns {void}
     */
    const clearTimer = (oCallback, sFunction) => {
        const timer = oCallback.timers[sFunction];
        timer !== undefined && timer.timer !== null && clearTimeout(timer.timer);
    };

    /**
     * Clear a callback timer for the specified function.
     *
     * @param {object} oRequest The request context object.
     * @param {string} sFunction The name of the function associated with the timer to be cleared.
     *
     * @returns {void}
     */
    self.clearTimer = (oRequest, sFunction) => getCallbacks(oRequest)
        .forEach(oCallback => clearTimer(oCallback, sFunction));
})(jaxon.ajax.callback, jaxon.utils.string, jaxon.config);


/**
 * Class: jaxon.ajax.handler
 */

(function(self, config, ajax, queue, dom) {
    /**
     * An array that is used internally in the jaxon.fn.handler object to keep track
     * of command handlers that have been registered.
     *
     * @var {object}
     */
    const handlers = {};

    /**
     * The queues that hold synchronous requests as they are sent and processed.
     *
     * @var {object}
     */
    self.q = {
        send: queue.create(config.requestQueueSize),
        recv: queue.create(config.requestQueueSize * 2)
    };

    /**
     * Registers a new command handler.
     *
     * @param {string} cmd The short name of the command handler.
     * @param {string} func The command handler function.
     * @param {string=''} name The full name of the command handler.
     *
     * @returns {void}
     */
    self.register = (cmd, func, name = '') => handlers[cmd] = { name, func };

    /**
     * Unregisters and returns a command handler.
     *
     * @param {string} cmd The name of the command handler.
     *
     * @returns {callable} The unregistered function.
     */
    self.unregister = (cmd) => {
        const handler = handlers[cmd];
        delete handlers[cmd];
        return handler.func;
    };

    /**
     * @param {object} command The response command to be executed.
     * @param {string} command.cmd The name of the function.
     *
     * @returns {boolean} (true or false): depending on whether a command handler has
     * been registered for the specified command (object).
     */
    self.isRegistered = ({ cmd }) => cmd !== undefined && handlers[cmd] !== undefined;

    /**
     * Perform a lookup on the command specified by the response command object passed
     * in the first parameter.  If the command exists, the function checks to see if
     * the command references a DOM object by ID; if so, the object is located within
     * the DOM and added to the command data.  The command handler is then called.
     * 
     * If the command handler returns true, it is assumed that the command completed
     * successfully.  If the command handler returns false, then the command is considered
     * pending; jaxon enters a wait state.  It is up to the command handler to set an
     * interval, timeout or event handler which will restart the jaxon response processing.
     * 
     * @param {object} command The response command to be executed.
     *
     * @returns {true} The command completed successfully.
     * @returns {false} The command signalled that it needs to pause processing.
     */
    self.execute = (command) => {
        if (!self.isRegistered(command)) {
            return true;
        }
        // If the command has an "id" attr, find the corresponding dom element.
        if (command.id) {
            command.target = dom.$(command.id);
        }
        // Process the command
        return self.call(command);
    };

    /**
     * Process a single command
     * 
     * @param {object} command The command to process
     *
     * @returns {boolean}
     */
    const processCommand = (command) => {
        try {
            self.execute(command);
            return true;
        } catch (e) {
            console.log(e);
        }
        return false;
    };

    /**
     * While entries exist in the queue, pull and entry out and process it's command.
     * When commandQueue.paused is set to true, the processing is halted.
     *
     * Note:
     * - Set commandQueue.paused to false and call this function to cause the queue processing to continue.
     * - When an exception is caught, do nothing; if the debug module is installed, it will catch the exception and handle it.
     *
     * @param {object} commandQueue A queue containing the commands to execute.
     *
     * @returns {true} The queue was fully processed and is now empty.
     * @returns {false} The queue processing was halted before the queue was fully processed.
     */
    self.processCommands = (commandQueue) => {
        // Stop processing the commands if the queue is paused.
        let command = null;
        while (!commandQueue.paused && (command = queue.pop(commandQueue)) !== null) {
            if (!processCommand(command)) {
                return true;
            }
        }
        return true;
    };

    /**
     * Calls the registered command handler for the specified command
     * (you should always check isRegistered before calling this function)
     *
     * @param {object} command The response command to be executed.
     * @param {string} command.cmd The name of the function.
     *
     * @returns {boolean}
     */
    self.call = (command) => {
        const handler = handlers[command.cmd];
        command.fullName = handler.name;
        return handler.func(command);
    }

    /**
     * Attempt to pop the next asynchronous request.
     *
     * @param {object} oQueue The queue object you would like to modify.
     *
     * @returns {object|null}
     */
    self.popAsyncRequest = oQueue => {
        if (queue.empty(oQueue) || queue.peek(oQueue).mode === 'synchronous') {
            return null;
        }
        return queue.pop(oQueue);
    }

    /**
     * Causes the processing of items in the queue to be delayed for the specified amount of time.
     * This is an asynchronous operation, therefore, other operations will be given an opportunity
     * to execute during this delay.
     *
     * @param {object} command The Response command object.
     * @param {integer} command.prop The number of 10ths of a second to sleep.
     * @param {object} command.response The command queue.
     *
     * @returns {true}
     */
    self.sleep = ({ prop: duration, response: commandQueue }) => {
        // The command queue is paused, and will be restarted after the specified delay.
        commandQueue.paused = true;
        setTimeout(() => {
            commandQueue.paused = false;
            self.processCommands(commandQueue);
        }, duration * 100);
        return true;
    };

    /**
     * Set or reset a timeout that is used to restart processing of the queue.
     *
     * This allows the queue to asynchronously wait for an event to occur (giving the browser time
     * to process pending events, like loading files)
     *
     * @param {object} command The Response command object.
     * @param {integer} interval The number of milliseconds to wait before starting/restarting the processing of the queue.
     *
     * @returns {void}
     */
    self.setWakeup = (command, interval) => {
        const { prop: duration, response: commandQueue } = command;
        if (command.retries === undefined) {
            command.retries = duration;
        }
        commandQueue.paused = false;
        if (command.retries-- > 0) {
            // Requeue the command and sleep for the given interval.
            queue.pushFront(commandQueue, command);
            self.sleep({ prop: interval, response: commandQueue });
        }
    };

    /**
     * Show the specified message.
     *
     * @param {string} message The message to display.
     *
     * @returns {void}
     */
    self.alert = (message) => ajax.message.info(message);

    /**
     * The function to run after the confirm question, for the comfirmCommands.
     *
     * @param {object} commandQueue The queue to process.
     * @param {integer} count The number of commands to skip.
     *
     * @returns {void}
     */
    const confirmCallback = (commandQueue, count) => {
        // The last entry in the queue is not a user command, thus it cannot be skipped.
        while (count > 0 && commandQueue.count > 1 && queue.pop(commandQueue) !== null) {
            --count;
        }
        // After => the processing is executed.
        commandQueue.paused = false;
        self.processCommands(commandQueue);
    };

    /**
     * Ask a confirm question and skip the specified number of commands if the answer is ok.
     *
     * The processing of the queue after the question is delayed so it occurs after this function returns.
     * before (when using the blocking confirm() function) or after this function returns.
     * @see confirmCallback
     *
     * @param {object} command The object to track the retry count for.
     * @param {integer} count The number of commands to skip.
     * @param {string} question The question to ask to the user.
     *
     * @returns {true}
     */
    self.confirm = (command, count, question) => {
        const { response: commandQueue } = command;
        // The command queue is paused, and will be restarted after the confirm question is answered.
        commandQueue.paused = true;
        ajax.message.confirm(question, '',
            () => confirmCallback(commandQueue, 0),
            () => confirmCallback(commandQueue, count));
        return true;
    };
})(jaxon.ajax.handler, jaxon.config, jaxon.ajax, jaxon.utils.queue, jaxon.utils.dom);


/**
 * Class: jaxon.ajax.parameters
 */

(function(self, str, version) {
    /**
     * The array of data bags
     *
     * @type {object}
     */
    self.bags = {};

    /**
     * Stringify a parameter of an ajax call.
     *
     * @param {mixed} oVal - The value to be stringified
     *
     * @returns {string}
     */
    const stringify = (oVal) => {
        if (oVal === undefined ||  oVal === null) {
            return '*';
        }
        const sType = str.typeOf(oVal);
        if (sType === 'object' || sType === 'array') {
            try {
                return encodeURIComponent(JSON.stringify(oVal));
            } catch (e) {
                oVal = '';
                // do nothing, if the debug module is installed
                // it will catch the exception and handle it
            }
        }
        oVal = encodeURIComponent(oVal);
        if (sType === 'string') {
            return 'S' + oVal;
        }
        if (sType === 'boolean') {
            return 'B' + oVal;
        }
        if (sType === 'number') {
            return 'N' + oVal;
        }
        return oVal;
    };

    /**
     * Make the databag object to send in the HTTP request.
     *
     * @param {array} aKeys The keys of values to get from the data bag.
     *
     * @return {object}
     */
    const getBagsParam = (aKeys) => JSON.stringify(aKeys.reduce((oValues, sKey) => ({
        ...oValues,
        [sKey]: self.bags[sKey] ?? '*' }
    ), {}));

    /**
     * Sets the request parameters in a container.
     *
     * @param {object} oRequest The request object
     * @param {object} oRequest.func The function to call on the server app.
     * @param {object} oRequest.parameters The parameters to pass to the function.
     * @param {array=} oRequest.bags The keys of values to get from the data bag.
     * @param {callable} fSetter A function that sets a single parameter
     *
     * @return {void}
     */
    const setParams = ({ func, parameters, bags = [] }, fSetter) => {
        const dNow = new Date();
        fSetter('jxnr', dNow.getTime());
        fSetter('jxnv', `${version.major}.${version.minor}.${version.patch}`);

        Object.keys(func).forEach(sParam => fSetter(sParam, encodeURIComponent(func[sParam])));

        // The parameters value was assigned from the js "arguments" var in a function. So it
        // is an array-like object, that we need to convert to a real array => [...parameters].
        // See https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/arguments
        [...parameters].forEach(xParam => fSetter('jxnargs[]', stringify(xParam)));

        bags.length > 0 && fSetter('jxnbags', encodeURIComponent(getBagsParam(bags)));
    };

    /**
     * Processes request specific parameters and store them in a FormData object.
     *
     * @param {object} oRequest
     *
     * @return {FormData}
     */
    const getFormDataParams = (oRequest) => {
        const rd = new FormData();
        setParams(oRequest, (sParam, sValue) => rd.append(sParam, sValue));

        // Files to upload
        const input = oRequest.upload.input;
        // The "input.files" var is an array-like object, that we need to convert to a real array.
        input.files && [...input.files].forEach(file => rd.append(input.name, file));
        return rd;
    };

    /**
     * Processes request specific parameters and store them in an URL encoded string.
     *
     * @param {object} oRequest
     *
     * @return {string}
     */
    const getUrlEncodedParams = (oRequest) => {
        const rd = [];
        setParams(oRequest, (sParam, sValue) => rd.push(sParam + '=' + sValue));

        if (oRequest.method === 'POST') {
            return rd.join('&');
        }
        // Move the parameters to the URL for HTTP GET requests
        oRequest.requestURI += oRequest.requestURI.indexOf('?') === -1 ? '?' : '&';
        oRequest.requestURI += rd.join('&');
        return ''; // The request body is empty
    };

    /**
     * Check if the request has files to upload.
     *
     * @param {object} oRequest The request object
     * @param {object} oRequest.upload The upload object
     *
     * @return {boolean}
     */
    const hasUpload = ({ upload }) => upload && upload.form && upload.input;

    /**
     * Processes request specific parameters and generates the temporary
     * variables needed by jaxon to initiate and process the request.
     *
     * Note:
     * This is called once per request; upon a request failure, this will not be called for additional retries.
     *
     * @param {object} oRequest The request object
     *
     * @return {void}
     */
    self.process = (oRequest) => {
        // Make request parameters.
        oRequest.requestURI = oRequest.URI;
        oRequest.requestData = hasUpload(oRequest) ?
            getFormDataParams(oRequest) : getUrlEncodedParams(oRequest);
    };
})(jaxon.ajax.parameters, jaxon.utils.string, jaxon.version);


/**
 * Class: jaxon.ajax.request
 */

(function(self, cfg, params, rsp, cbk, handler, upload, queue) {
    /**
     * Initialize a request object, populating default settings, where call specific
     * settings are not already provided.
     *
     * @param {object} oRequest An object that specifies call specific settings that will,
     *      in addition, be used to store all request related values.
     *      This includes temporary values used internally by jaxon.
     *
     * @returns {boolean}
     */
    self.initialize = (oRequest) => {
        cfg.setRequestOptions(oRequest);

        cbk.initCallbacks(oRequest);
        cbk.execute(oRequest, 'onInitialize');

        oRequest.status = (oRequest.statusMessages) ? cfg.status.update : cfg.status.dontUpdate;
        oRequest.cursor = (oRequest.waitCursor) ? cfg.cursor.update : cfg.cursor.dontUpdate;

        // Look for upload parameter
        upload.initialize(oRequest);
    };

    /**
     * Prepare a request, by setting the HTTP options, handlers and processor.
     *
     * @param {object} oRequest The request context object.
     *
     * @return {void}
     */
    self.prepare = (oRequest) => {
        --oRequest.requestRetry;
        cbk.execute(oRequest, 'onPrepare');

        oRequest.httpRequestOptions = {
            ...cfg.httpRequestOptions,
            method: oRequest.method,
            headers: {
                ...oRequest.commonHeaders,
                ...(oRequest.method === 'POST' ? oRequest.postHeaders : oRequest.getHeaders),
            },
            body: oRequest.requestData,
        };

        oRequest.responseConverter = (response) => {
            // Save the reponse object
            oRequest.response = response;
            // Get the response content
            return oRequest.convertResponseToJson ? response.json() : response.text();
        };
        oRequest.responseHandler = (responseContent) => {
            oRequest.responseContent = responseContent;
            // Synchronous request are processed immediately.
            // Asynchronous request are processed only if the queue is empty.
            if (queue.empty(handler.q.send) || oRequest.mode === 'synchronous') {
                rsp.received(oRequest);
            } else {
                queue.push(handler.q.recv, oRequest);
            }
        };
        oRequest.errorHandler = (error) => {
            cbk.execute(oRequest, 'onFailure');
            throw error;
        };
        if (!oRequest.responseProcessor) {
            oRequest.responseProcessor = rsp.jsonProcessor;
        }

        // No request is submitted while there are pending requests in the outgoing queue.
        const submitRequest = queue.empty(handler.q.send);
        if (oRequest.mode === 'synchronous') {
            // Synchronous requests are always queued, in both send and recv queues.
            queue.push(handler.q.send, oRequest);
            queue.push(handler.q.recv, oRequest);
            return submitRequest;
        }
        // Asynchronous requests are queued in send queue only if they are not submitted.
        submitRequest || queue.push(handler.q.send, oRequest);
        return submitRequest;
    };

    /**
     * Clean up the request object.
     *
     * @param {object} oRequest The request context object.
     *
     * @returns {void}
     */
    const cleanUp = (oRequest) => {
        // clean up -- these items are restored when the request is initiated
        delete oRequest.func;
        delete oRequest.URI;
        delete oRequest.requestURI;
        delete oRequest.requestData;
        delete oRequest.requestRetry;
        delete oRequest.httpRequestOptions;
        delete oRequest.responseHandler;
        delete oRequest.responseConverter;
        delete oRequest.responseContent;
        delete oRequest.response;
        delete oRequest.errorHandler;
    };

    /**
     * Called by the response command queue processor when all commands have been processed.
     *
     * @param {object} oRequest The request context object.
     *
     * @return {void}
     */
    self.complete = (oRequest) => {
        cbk.execute(oRequest, 'onComplete');
        oRequest.cursor.onComplete();
        oRequest.status.onComplete();

        cleanUp(oRequest);

        // All the requests and responses queued while waiting must now be processed.
        if(oRequest.mode === 'synchronous') {
            // Remove the current request from the send and recv queues.
            queue.pop(handler.q.send);
            queue.pop(handler.q.recv);
            // Process the asynchronous responses received while waiting.
            while((recvRequest = handler.popAsyncRequest(handler.q.recv)) !== null) {
                rsp.received(recvRequest);
            }
            // Submit the asynchronous requests sent while waiting.
            while((nextRequest = handler.popAsyncRequest(handler.q.send)) !== null) {
                self.submit(nextRequest);
            }
            // Submit the next synchronous request, if there's any.
            if((nextRequest = queue.peek(handler.q.send)) !== null) {
                self.submit(nextRequest);
            }
        }
    };

    /**
     * Send a request.
     *
     * @param {object} oRequest The request context object.
     *
     * @returns {void}
     */
    self._send = (oRequest) => {
        fetch(oRequest.requestURI, oRequest.httpRequestOptions)
            .then(oRequest.responseConverter)
            .then(oRequest.responseHandler)
            .catch(oRequest.errorHandler);
    };

    /**
     * Create a request object and submit the request using the specified request type;
     * all request parameters should be finalized by this point.
     * Upon failure of a POST, this function will fall back to a GET request.
     *
     * @param {object} oRequest The request context object.
     *
     * @returns {mixed}
     */
    self.submit = (oRequest) => {
        oRequest.status.onRequest();

        cbk.execute(oRequest, 'onResponseDelay');
        cbk.execute(oRequest, 'onExpiration');
        cbk.execute(oRequest, 'onRequest');

        oRequest.cursor.onWaiting();
        oRequest.status.onWaiting();

        self._send(oRequest);

        return oRequest.returnValue;
    };

    /**
     * Abort the request.
     *
     * @param {object} oRequest The request context object.
     *
     * @returns {void}
     */
    self.abort = (oRequest) => {
        oRequest.aborted = true;
        self.complete(oRequest);
    };

    /**
     * Initiates a request to the server.
     *
     * @param {object} func An object containing the name of the function to
     *      execute on the server. The standard request is: {jxnfun:'function_name'}
     * @param {object=} funcArgs A request object which may contain call specific parameters.
     *      This object will be used by jaxon to store all the request parameters as well as
     *      temporary variables needed during the processing of the request.
     *
     * @returns {boolean}
     */
    self.execute = (func, funcArgs) => {
        if (func === undefined) {
            return false;
        }

        const oRequest = funcArgs ?? {};
        oRequest.func = func;

        self.initialize(oRequest);

        // Process the request parameters
        cbk.execute(oRequest, 'onProcessParams');
        params.process(oRequest);

        while (oRequest.requestRetry > 0) {
            try {
                return self.prepare(oRequest) ? self.submit(oRequest) : null;
            }
            catch (e) {
                cbk.execute(oRequest, 'onFailure');
                if (oRequest.requestRetry <= 0) {
                    throw e;
                }
            }
        }
        return true;
    };
})(jaxon.ajax.request, jaxon.config, jaxon.ajax.parameters, jaxon.ajax.response,
    jaxon.ajax.callback, jaxon.ajax.handler, jaxon.utils.upload, jaxon.utils.queue);


/**
 * Class: jaxon.ajax.response
 */

(function(self, config, handler, req, cbk, queue) {
    /**
     * This array contains a list of codes which will be returned from the server upon
     * successful completion of the server portion of the request.
     *
     * These values should match those specified in the HTTP standard.
     *
     * @var {array}
     */
    const successCodes = [0, 200];

    // 10.4.1 400 Bad Request
    // 10.4.2 401 Unauthorized
    // 10.4.3 402 Payment Required
    // 10.4.4 403 Forbidden
    // 10.4.5 404 Not Found
    // 10.4.6 405 Method Not Allowed
    // 10.4.7 406 Not Acceptable
    // 10.4.8 407 Proxy Authentication Required
    // 10.4.9 408 Request Timeout
    // 10.4.10 409 Conflict
    // 10.4.11 410 Gone
    // 10.4.12 411 Length Required
    // 10.4.13 412 Precondition Failed
    // 10.4.14 413 Request Entity Too Large
    // 10.4.15 414 Request-URI Too Long
    // 10.4.16 415 Unsupported Media Type
    // 10.4.17 416 Requested Range Not Satisfiable
    // 10.4.18 417 Expectation Failed
    // 10.5 Server Error 5xx
    // 10.5.1 500 Internal Server Error
    // 10.5.2 501 Not Implemented
    // 10.5.3 502 Bad Gateway
    // 10.5.4 503 Service Unavailable
    // 10.5.5 504 Gateway Timeout
    // 10.5.6 505 HTTP Version Not Supported

    /**
     * This array contains a list of status codes returned by the server to indicate
     * that the request failed for some reason.
     *
     * @var {array}
     */
    const errorCodes = [400, 401, 402, 403, 404, 500, 501, 502, 503];

    // 10.3.1 300 Multiple Choices
    // 10.3.2 301 Moved Permanently
    // 10.3.3 302 Found
    // 10.3.4 303 See Other
    // 10.3.5 304 Not Modified
    // 10.3.6 305 Use Proxy
    // 10.3.7 306 (Unused)
    // 10.3.8 307 Temporary Redirect

    /**
     * An array of status codes returned from the server to indicate a request for redirect to another URL.
     *
     * Typically, this is used by the server to send the browser to another URL.
     * This does not typically indicate that the jaxon request should be sent to another URL.
     *
     * @var {array}
     */
    const redirectCodes = [301, 302, 307];

    /**
     * Check if a status code indicates a success.
     *
     * @param {int} nStatusCode A status code.
     *
     * @return {bool}
     */
    self.isSuccessCode = nStatusCode => successCodes.indexOf(nStatusCode) >= 0;

    /**
     * Check if a status code indicates a redirect.
     *
     * @param {int} nStatusCode A status code.
     *
     * @return {bool}
     */
    self.isRedirectCode = nStatusCode => redirectCodes.indexOf(nStatusCode) >= 0;

    /**
     * Check if a status code indicates an error.
     *
     * @param {int} nStatusCode A status code.
     *
     * @return {bool}
     */
    self.isErrorCode = nStatusCode => errorCodes.indexOf(nStatusCode) >= 0;

    /**
     * Parse the JSON response into a series of commands.
     *
     * @param {object} oRequest The request context object.
     *
     * @return {int}
     */
    const queueCommands = (oRequest) => {
        const responseContent = oRequest.responseContent;
        if (!responseContent || !responseContent.jxnobj) {
            return 0;
        }

        oRequest.status.onProcessing();

        if (responseContent.jxnrv) {
            oRequest.returnValue = responseContent.jxnrv;
        }

        responseContent.debugmsg && console.log(responseContent.debugmsg);

        let nSequence = 0;
        responseContent.jxnobj.forEach(command => queue.push(oRequest.commandQueue, {
            fullName: '*unknown*',
            ...command,
            sequence: nSequence++,
            response: oRequest.commandQueue,
            request: oRequest,
            context: oRequest.context,
        }));

        return nSequence;
    };

    /**
     * Queue the last command that is will end the response processing.
     *
     * @param {object} oRequest The request context object.
     * @param {int} nSequence The last command sequence
     *
     * @return {int}
     */
    const queueEndCommand = (oRequest, nSequence) => queue.push(oRequest.commandQueue, {
        fullName: 'Response Complete',
        sequence: nSequence,
        request: oRequest,
        context: oRequest.context,
        cmd: 'rcmplt',
    });

    /**
     * This is the JSON response processor.
     *
     * @param {object} oRequest The request context object.
     *
     * @return {mixed}
     */
    self.jsonProcessor = (oRequest) => {
        const status = oRequest.response.status;
        if (self.isSuccessCode(status)) {
            cbk.execute(oRequest, 'onSuccess');
            // Queue and process the commands in the response.
            const nSequence = queueCommands(oRequest)
            queueEndCommand(oRequest, nSequence);
            handler.processCommands(oRequest.commandQueue);
        }
        else if (self.isRedirectCode(status)) {
            cbk.execute(oRequest, 'onRedirect');
            req.complete(oRequest);
            window.location = oRequest.response.headers.get('location');
        }
        else if (self.isErrorCode(status)) {
            cbk.execute(oRequest, 'onFailure');
            req.complete(oRequest);
        }

        return oRequest.returnValue;
    };

    /**
     * Process the response.
     *
     * @param {object} oRequest The request context object.
     *
     * @return {mixed}
     */
    self.received = (oRequest) => {
        // Sometimes the response.received gets called when the request is aborted
        if (oRequest.aborted) {
            return null;
        }

        // Create a queue for the commands in the response.
        oRequest.commandQueue = queue.create(config.commandQueueSize);

        // The response is successfully received, clear the timers for expiration and delay.
        cbk.clearTimer(oRequest, 'onExpiration');
        cbk.clearTimer(oRequest, 'onResponseDelay');
        cbk.execute(oRequest, 'beforeResponseProcessing');

        return oRequest.responseProcessor(oRequest);
    };
})(jaxon.ajax.response, jaxon.config, jaxon.ajax.handler, jaxon.ajax.request,
    jaxon.ajax.callback, jaxon.utils.queue);


/**
 * Class: jaxon.cmd.body
 */

(function(self, dom, baseDocument) {
    /**
     * Assign an element's attribute to the specified value.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The HTML element to effect.
     * @param {string} command.prop The name of the attribute to set.
     * @param {string} command.data The new value to be applied.
     *
     * @returns {true} The operation completed successfully.
     */
    self.assign = ({ target: element, prop: property, data: value }) => {
        const xElt = dom.getInnerObject(property, element);
        if (xElt !== null) {
            xElt.node[xElt.attr] = value;
        }
        return true;
    };

    /**
     * Append the specified value to an element's attribute.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The HTML element to effect.
     * @param {string} command.prop The name of the attribute to append to.
     * @param {string} command.data The new value to be appended.
     *
     * @returns {true} The operation completed successfully.
     */
    self.append = ({ target: element, prop: property, data: value }) => {
        const xElt = dom.getInnerObject(property, element);
        if (xElt !== null) {
            xElt.node[xElt.attr] = xElt.node[xElt.attr] + value;
        }
        return true;
    };

    /**
     * Prepend the specified value to an element's attribute.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The HTML element to effect.
     * @param {string} command.prop The name of the attribute.
     * @param {string} command.data The new value to be prepended.
     *
     * @returns {true} The operation completed successfully.
     */
    self.prepend = ({ target: element, prop: property, data: value }) => {
        const xElt = dom.getInnerObject(property, element);
        if (xElt !== null) {
            xElt.node[xElt.attr] = value + xElt.node[xElt.attr];
        }
        return true;
    };

    /**
     * Replace a text in the value of a given property in an element
     *
     * @param {object} xElt The value returned by the dom.getInnerObject() function
     * @param {string} sSearch The text to search
     * @param {string} sReplace The text to use as replacement
     *
     * @returns {void}
     */
    const replaceText = (xElt, sSearch, sReplace) => {
        const bFunction = (typeof xElt.node[xElt.attr] === 'function');
        const sCurText = bFunction ? xElt.node[xElt.attr].join('') : xElt.node[xElt.attr];
        const sNewText = sCurText.replaceAll(sSearch, sReplace);
        if (bFunction || dom.willChange(xElt.node, xElt.attr, sNewText)) {
            xElt.node[xElt.attr] = sNewText;
        }
    };

    /**
     * Search and replace the specified text.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The element which is to be modified.
     * @param {string} command.prop The name of the attribute to be set.
     * @param {object} command.data The search text and replacement text.
     * @param {object} command.data.s The search text.
     * @param {object} command.data.r The replacement text.
     *
     * @returns {true} The operation completed successfully.
     */
    self.replace = ({ target: element, prop, data: { s: search, r: replace } }) => {
        const xElt = dom.getInnerObject(prop, element);
        if (xElt !== null) {
            replaceText(xElt, prop === 'innerHTML' ? dom.getBrowserHTML(search) : search, replace);
        }
        return true;
    };

    /**
     * Delete an element.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The element which will be deleted.
     *
     * @returns {true} The operation completed successfully.
     */
    self.remove = ({ target: element }) => {
        dom.removeElement(element);
        return true;
    };

    /**
     * Create a new element and append it to the specified parent element.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The element which will contain the new element.
     * @param {string} command.data The tag name for the new element.
     * @param {string} command.prop The value to be assigned to the id attribute of the new element.
     *
     * @returns {true} The operation completed successfully.
     */
    self.create = ({ target: element, data: sTag, prop: sId }) => {
        if (element) {
            const target = baseDocument.createElement(sTag);
            target.setAttribute('id', sId);
            element.appendChild(target);
        }
        return true;
    };

    /**
     * Insert a new element before the specified element.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The element that will be used as the reference point for insertion.
     * @param {string} command.data The tag name for the new element.
     * @param {string} command.prop The value that will be assigned to the new element's id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.insert = ({ target: element, data: sTag, prop: sId }) => {
        if (element && element.parentNode) {
            const target = baseDocument.createElement(sTag);
            target.setAttribute('id', sId);
            element.parentNode.insertBefore(target, element);
        }
        return true;
    };

    /**
     * Insert a new element after the specified element.
     *
     * @param {object} command The Response command object.
     * @param {Element} command.target The element that will be used as the reference point for insertion.
     * @param {string} command.data The tag name for the new element.
     * @param {string} command.prop The value that will be assigned to the new element's id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.insertAfter = ({ target: element, data: sTag, prop: sId }) => {
        if (element && element.parentNode) {
            const target = baseDocument.createElement(sTag);
            target.setAttribute('id', sId);
            element.parentNode.insertBefore(target, element.nextSibling);
        }
        return true;
    };

    /**
     * Assign a value to a named member of the current script context object.
     *
     * @param {object} command The Response command object.
     * @param {string} command.prop The name of the member to assign.
     * @param {string|object} command.data The value to assign to the member.
     * @param {object} command.context The current script context object which is accessable via the 'this' keyword.
     *
     * @returns {true} The operation completed successfully.
     */
    self.contextAssign = ({ context, prop: sAttribute, data: value }) => {
        const xElt = dom.getInnerObject(sAttribute, context);
        if (xElt !== null) {
            xElt.node[xElt.attr] = value;
        }
        return true;
    };

    /**
     * Appends a value to a named member of the current script context object.
     *
     * @param {object} command The Response command object.
     * @param {string} command.prop The name of the member to append to.
     * @param {string|object} command.data The value to append to the member.
     * @param {object} command.context The current script context object which is accessable via the 'this' keyword.
     *
     * @returns {true} The operation completed successfully.
     */
    self.contextAppend = ({ context, prop: sAttribute, data: value }) => {
        const xElt = dom.getInnerObject(sAttribute, context);
        if (xElt !== null) {
            xElt.node[xElt.attr] = xElt.node[xElt.attr] + value;
        }
        return true;
    };

    /**
     * Prepend a value to a named member of the current script context object.
     *
     * @param {object} command The Response command object.
     * @param {string} command.prop The name of the member to prepend to.
     * @param {string|object} command.data The value to prepend to the member.
     * @param {object} command.context The current script context object which is accessable via the 'this' keyword.
     *
     * @returns {true} The operation completed successfully.
     */
    self.contextPrepend = ({ context, prop: sAttribute, data: value }) => {
        const xElt = dom.getInnerObject(sAttribute, context);
        if (xElt !== null) {
            xElt.node[xElt.attr] = value + xElt.node[xElt.attr];
        }
        return true;
    };
})(jaxon.cmd.body, jaxon.utils.dom, jaxon.config.baseDocument);


/**
 * Class: jaxon.cmd.event
 */

(function(self, dom, str, script) {
    /**
     *  Set an event handler.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The target element
     * @param {string} command.prop The event name
     * @param {string} command.data The callback code
     *
     * @returns {true} The operation completed successfully.
     */
    self.setEvent = ({ target: oTarget, prop: sEvent, data: sCode }) => {
        dom.createFunction(`(e) => { ${str.doubleQuotes(sCode)} }`);
        oTarget[str.addOnPrefix(sEvent)] = script.context.delegateCall;
        return true;
    };

    /**
     * Add an event handler to the specified target.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The target element
     * @param {string} command.prop The name of the event.
     * @param {string} command.data The name of the function to be called
     *
     * @returns {true} The operation completed successfully.
     */
    self.addHandler = ({ target, prop: sEvent, data: sFuncName }) => {
        target.addEventListener(str.stripOnPrefix(sEvent), dom.findFunction(sFuncName), false)
        return true;
    };

    /**
     * Remove an event handler from an target.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The target element
     * @param {string} command.prop The name of the event.
     * @param {string} command.data The name of the function to be removed
     *
     * @returns {true} The operation completed successfully.
     */
    self.removeHandler = ({ target, prop: sEvent, data: sFuncName }) => {
       target.removeEventListener(str.stripOnPrefix(sEvent), dom.findFunction(sFuncName), false);
       return true;
    };
})(jaxon.cmd.event, jaxon.utils.dom, jaxon.utils.string, jaxon.cmd.script);


/**
 * Class: jaxon.cmd.form
 */

(function(self, baseDocument) {
    /**
     * Create and return a form input element with the specified parameters.
     *
     * @param {string} type The type of input element desired.
     * @param {string} name The value to be assigned to the name attribute.
     * @param {string} id The value to be assigned to the id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    const getInput = (type, name, id) => {
        const oInput = baseDocument.createElement('input');
        oInput.setAttribute('type', type);
        oInput.setAttribute('name', name);
        oInput.setAttribute('id', id);
        return oInput;
    };

    /**
     * Create a new input element under the specified parent.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The element that will be used as the reference for the insertion.
     * @param {string} command.type The value to be assigned to the type attribute.
     * @param {string} command.data The value to be assigned to the name attribute.
     * @param {string} command.prop The value to be assigned to the id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.createInput = ({ target: objParent, type: sType, data: sName, prop: sId }) => {
        const target = getInput(sType, sName, sId);
        if (objParent && target) {
            objParent.appendChild(target);
        }
        return true;
    };

    /**
     * Insert a new input element before the specified element.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The element that will be used as the reference for the insertion.
     * @param {string} command.type The value to be assigned to the type attribute.
     * @param {string} command.data The value to be assigned to the name attribute.
     * @param {string} command.prop The value to be assigned to the id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.insertInput = ({ target: objSibling, type: sType, data: sName, prop: sId }) => {
        const target = getInput(sType, sName, sId);
        if (target && objSibling && objSibling.parentNode) {
            objSibling.parentNode.insertBefore(target, objSibling);
        }
        return true;
    };

    /**
     * Insert a new input element after the specified element.
     *
     * @param {object} command The Response command object.
     * @param {string} command.id The target element id
     * @param {object} command.target The element that will be used as the reference for the insertion.
     * @param {string} command.type The value to be assigned to the type attribute.
     * @param {string} command.data The value to be assigned to the name attribute.
     * @param {string} command.prop The value to be assigned to the id attribute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.insertInputAfter = ({ target: objSibling, type: sType, data: sName, prop: sId }) => {
        const target = getInput(sType, sName, sId);
        if (target && objSibling && objSibling.parentNode) {
            objSibling.parentNode.insertBefore(target, objSibling.nextSibling);
        }
        return true;
    };
})(jaxon.cmd.form, jaxon.config.baseDocument);


/**
 * Class: jaxon.cmd.head
 */

(function(self, handler, baseDocument) {
    /**
     * Add a reference to the specified script file if one does not already exist in the HEAD of the current document.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The URI of the file.
     *
     * @returns {true} The operation completed successfully.
     */
    self.includeScriptOnce = ({ data: src, type = 'text/javascript', elm_id }) => {
        // Check for existing script tag for this file.
        const loadedScripts = baseDocument.getElementsByTagName('script');
        // Find an existing script with the same file name
        const loadedScript = Array.from(loadedScripts)
            .find(script => script.src && script.src.indexOf(src) >= 0);
        return (loadedScript) ? true : self.includeScript({ data: src, type, elm_id });
    };

    /**
     * Adds a SCRIPT tag referencing the specified file.
     * This effectively causes the script to be loaded in the browser.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The URI of the file.
     * @param {string='text/javascript'} command.type The type of the file.
     * @param {string=} command.elm_id The script tag id.
     *
     * @returns {true} The operation completed successfully.
     */
    self.includeScript = ({ data: src, type = 'text/javascript', elm_id }) => {
        const objHead = baseDocument.getElementsByTagName('head');
        const objScript = baseDocument.createElement('script');
        objScript.src = src;
        objScript.type = type;
        if (elm_id) {
            objScript.setAttribute('id', elm_id);
        }
        objHead[0].appendChild(objScript);
        return true;
    };

    /**
     * Locates a SCRIPT tag in the HEAD of the document which references the specified file and removes it.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The URI of the file.
     * @param {string=} command.unld A function to execute.
     *
     * @returns {true} The operation completed successfully.
     */
    self.removeScript = ({ data: src, unld: unload }) => {
        const loadedScripts = baseDocument.getElementsByTagName('script');
        // Find an existing script with the same file name
        const loadedScript = Array.from(loadedScripts)
            .find(script => script.src && script.src.indexOf(src) >= 0);
        if (!loadedScript) {
            return true;
        }
        if (unload) {
            // Execute the provided unload function.
            self.execute({ data: unload, context: window });
        }
        loadedScript.parentNode.removeChild(loadedScript);
        return true;
    };

    /**
     * Add a LINK reference to the specified .css file if it does not already exist in the HEAD of the current document.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The URI of the .css file to reference.
     * @param {string='screen'} command.media The media type of the css file (print/screen/handheld,..)
     *
     * @returns {true} The operation completed successfully.
     */
    self.includeCSS = ({ data: fileName, media = 'screen' }) => {
        const oHeads = baseDocument.getElementsByTagName('head');
        const oHead = oHeads[0];
        const found = Array.from(oHead.getElementsByTagName('link'))
            .find(link => link.href.indexOf(fileName) >= 0 && link.media == media);
        if (found) {
            return true;
        }

        const oCSS = baseDocument.createElement('link');
        oCSS.rel = 'stylesheet';
        oCSS.type = 'text/css';
        oCSS.href = fileName;
        oCSS.media = media;
        oHead.appendChild(oCSS);
        return true;
    };

    /**
     * Locate and remove a LINK reference from the current document's HEAD.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The URI of the .css file.
     * @param {string='screen'} command.media The media type of the css file (print/screen/handheld,..)
     *
     * @returns {true} The operation completed successfully.
     */
    self.removeCSS = ({ data: fileName, media = 'screen' }) => {
        const oHeads = baseDocument.getElementsByTagName('head');
        const oHead = oHeads[0];
        const oLinks = oHead.getElementsByTagName('link');
        oLinks.filter(link => link.href.indexOf(fileName) >= 0 && link.media === media)
            .forEach(link => oHead.removeChild(link));
        return true;
    },

    /**
     * Attempt to detect when all .css files have been loaded once they are referenced by a LINK tag
     * in the HEAD of the current document.
     *
     * @param {object} command The Response command object.
     * @param {integer} command.prop The number of 1/10ths of a second to wait before giving up.
     * @param {object} command.response The Response object.
     *
     * @returns {true} The .css files appear to be loaded.
     * @returns {false} The .css files do not appear to be loaded and the timeout has not expired.
     */
    self.waitForCSS = (command) => {
        const oDocSS = baseDocument.styleSheets;
        const ssLoaded = Array.from(oDocSS).every(styleSheet => {
            const enabled = styleSheet.cssRules.length ?? styleSheet.rules.length ?? 0;
            return enabled !== 0;
        });
        if (ssLoaded) {
            return true;
        }

        // Inject a delay in the queue processing and handle retry counter
        handler.setWakeup(command, 10);
        return true;
    };
})(jaxon.cmd.head, jaxon.ajax.handler, jaxon.config.baseDocument);


/**
 * Class: jaxon.cmd.script
 */

(function(self, handler, dom, str, queue) {
    /**
     * Show the specified message.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The message to display.
     *
     * @returns {true} The operation completed successfully.
     */
    self.alert = ({ data: message }) => {
        handler.alert(message);
        return true;
    };

    /**
     * Prompt the user with the specified question, if the user responds by clicking cancel,
     * then skip the specified number of commands in the response command queue.
     * If the user clicks Ok, the command processing resumes normal operation.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The question to ask.
     * @param {integer} command.count The number of commands to skip.
     *
     * @returns {false} Stop the processing of the command queue until the user answers the question.
     */
    self.confirm = (command) => {
        const { count, data: question } = command;
        handler.confirm(command, count, question);
        return false;
    };

    /**
     * Call a javascript function with a series of parameters using the current script context.
     *
     * @param {object} command The Response command object.
     * @param {array} command.data  The parameters to pass to the function.
     * @param {string} command.func The name of the function to call.
     * @param {object} command.context The javascript object to be referenced as 'this' in the script.
     *
     * @returns {true} The operation completed successfully.
     */
    self.call = ({ func: sFuncName, data: aFuncParams, context = {} }) => {
        self.context = context;
        const func = dom.findFunction(sFuncName);
        func && func.apply(self.context, aFuncParams);
        return true;
    };

    /**
     * Execute the specified string of javascript code, using the current script context.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The javascript to be evaluated.
     * @param {object} command.context The javascript object to be referenced as 'this' in the script.
     *
     * @returns {true} The operation completed successfully.
     */
    self.execute = ({ data: funcBody, context = {} }) => {
        self.context = context;
        const jsCode = `() => {
    ${funcBody}
}`;

        dom.createFunction(jsCode) && self.context.delegateCall();
        return true;
    };

    /**
     * Test for the specified condition, using the current script context;
     * if the result is false, sleep for 1/10th of a second and try again.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The javascript to evaluate.
     * @param {integer} command.prop The number of 1/10ths of a second to wait before giving up.
     * @param {object} command.context The javascript object to be referenced as 'this' in the script.
     *
     * @returns {true} The condition evaluates to true or the sleep time has expired.
     * @returns {false} The condition evaluates to false and the sleep time has not expired.
     */
    self.waitFor = (command) => {
        const { data: funcBody, context = {} } = command;
        self.context = context;
        const jsCode = `() => {
    return (${funcBody});
}`;

        if (dom.createFunction(jsCode) && self.context.delegateCall()) {
            return true;
        }

        // Inject a delay in the queue processing and handle retry counter
        handler.setWakeup(command, 100);
        return true;
    };

    /**
     * Get function parameters as string
     *
     * @param {string} parameters 
     */
    const getParameters = (parameters) => {
        if (parameters === undefined) {
            return '';
        }
        const sType = str.typeOf(parameters);
        if (sType === 'array') {
            return parameters.join(', ');
        }
        if (sType === 'object') {
            return parameters.values().join(', ');
        }
        return parameters;
    };

    /**
     * Constructs the specified function using the specified javascript as the body of the function.
     *
     * @param {object} command The Response command object.
     * @param {string} command.func The name of the function to construct.
     * @param {string} command.data The script that will be the function body.
     * @param {object} command.context The javascript object to be referenced as 'this' in the script.
     *
     * @returns {true} The operation completed successfully.
     */
    self.setFunction = ({ func: sFuncName, data: funcBody, prop: aFuncParams, context = {} }) => {
        self.context = context;
        const jsCode = `(${getParameters(aFuncParams)}) => {
    ${funcBody}
}`;

        dom.createFunction(jsCode, sFuncName);
        return true;
    };

    self.wrapped = {}; // Original wrapped functions will be saved here.

    /**
     * Construct a javascript function which will call the original function with the same name,
     * potentially executing code before and after the call to the original function.
     *
     * @param {object} command The Response command object.
     * @param {string} command.func The name of the function to be wrapped.
     * @param {string} command.prop List of parameters used when calling the function.
     * @param {array} command.data The portions of code to be called before, after 
     *   or even between calls to the original function.
     * @param {object} command.context The javascript object to be referenced as 'this' in the script.
     *
     * @returns {true} The operation completed successfully.
     */
    self.wrapFunction = ({ func: sFuncName, type: returnType, prop: aFuncParams,
        data: [funcCodeBefore, funcCodeAfter = '// No call after'], context = {} }) => {
        self.context = context;
        const func = dom.findFunction(sFuncName);
        if (!func) {
            return true;
        }

        // Save the existing function
        const wrappedFuncName = sFuncName.toLowerCase().replaceAll('.', '_');
        if (!self.wrapped[wrappedFuncName]) {
            self.wrapped[wrappedFuncName] = func;
        }

        const varDefine = returnType ? `let ${returnType} = null;` : '// No return value';
        const varAssign = returnType ? `${returnType} = ` : '';
        const varReturn = returnType ? `return ${returnType};` : '// No return value';

        const jsCode = `(${getParameters(aFuncParams)}) => {
    ${varDefine}
    ${funcCodeBefore}

    const wrappedFuncName = "${sFuncName}".toLowerCase().replaceAll('.', '_');
    // Call the wrapped function (saved in jaxon.cmd.script.wrapped) with the same parameters.
    ${varAssign}jaxon.cmd.script.wrapped[wrappedFuncName](${aFuncParams});
    ${funcCodeAfter}
    ${varReturn}
}`;

        dom.createFunction(jsCode) && self.context.delegateCall();
        return true;
    };

    /**
     * Redirects the browser to the specified URL.
     *
     * @param {object} command The Response command object.
     * @param {string} command.data The new URL to redirect to
     * @param {integer} command.delay The time to wait before the redirect.
     *
     * @returns {true} The operation completed successfully.
     */
    self.redirect = ({ data: sUrl, delay: nDelay }) => {
        if (nDelay <= 0) {
            window.location = sUrl;
            return true;
        }
        window.setTimeout(() => window.location = sUrl, nDelay * 1000);
        return true;
    };
})(jaxon.cmd.script, jaxon.ajax.handler, jaxon.utils.dom, jaxon.utils.string, jaxon.utils.queue);


/*
    File: jaxon.js

    This file contains the definition of the main jaxon javascript core.

    This is the client side code which runs on the web browser or similar web enabled application.
    Include this in the HEAD of each page for which you wish to use jaxon.
*/

/**
 * Initiates a request to the server.
 */
jaxon.request = jaxon.ajax.request.execute;

/**
 * Registers a new command handler.
 * Shortcut to <jaxon.ajax.handler.register>
 */
jaxon.register = jaxon.ajax.handler.register;

/**
 * Shortcut to <jaxon.utils.dom.$>.
 */
jaxon.$ = jaxon.utils.dom.$;

/**
 * Shortcut to <jaxon.utils.form.getValues>.
 */
jaxon.getFormValues = jaxon.utils.form.getValues;

/**
 * Prints various types of messages on the user screen.
 */
jaxon.msg = jaxon.ajax.message;

/**
 * Shortcut to <jaxon.cmd.script>.
 */
jaxon.js = jaxon.cmd.script;

/**
 * Indicates if jaxon module is loaded.
 */
jaxon.isLoaded = true;

/**
 * Register the command handlers provided by the library, and initialize the message object.
 */
(function(register, cmd, ajax) {
    register('rcmplt', ({ request }) => {
        ajax.request.complete(request);
        return true;
    }, 'Response complete');

    register('ino', cmd.head.includeScriptOnce, 'includeScriptOnce');
    register('in', cmd.head.includeScript, 'includeScript');
    register('rjs', cmd.head.removeScript, 'removeScript');
    register('css', cmd.head.includeCSS, 'includeCSS');
    register('rcss', cmd.head.removeCSS, 'removeCSS');
    register('wcss', cmd.head.waitForCSS, 'waitForCSS');

    register('as', cmd.body.assign, 'assign/clear');
    register('ap', cmd.body.append, 'append');
    register('pp', cmd.body.prepend, 'prepend');
    register('rp', cmd.body.replace, 'replace');
    register('rm', cmd.body.remove, 'remove');
    register('ce', cmd.body.create, 'create');
    register('ie', cmd.body.insert, 'insert');
    register('ia', cmd.body.insertAfter, 'insertAfter');
    register('c:as', cmd.body.contextAssign, 'context assign');
    register('c:ap', cmd.body.contextAppend, 'context append');
    register('c:pp', cmd.body.contextPrepend, 'context prepend');

    register('s', ajax.handler.sleep, 'sleep');
    register('wf', cmd.script.waitFor, 'waitFor');
    register('js', cmd.script.execute, 'execute Javascript');
    register('jc', cmd.script.call, 'call js function');
    register('sf', cmd.script.setFunction, 'setFunction');
    register('wpf', cmd.script.wrapFunction, 'wrapFunction');
    register('al', cmd.script.alert, 'alert');
    register('cc', cmd.script.confirm, 'confirm');
    register('rd', cmd.script.redirect, 'redirect');

    register('ci', cmd.form.createInput, 'createInput');
    register('ii', cmd.form.insertInput, 'insertInput');
    register('iia', cmd.form.insertInputAfter, 'insertInputAfter');

    register('ev', cmd.event.setEvent, 'setEvent');
    register('ah', cmd.event.addHandler, 'addHandler');
    register('rh', cmd.event.removeHandler, 'removeHandler');

    register('dbg', ({ data: message }) => {
        console.log(message);
        return true;
    }, 'Debug message');

    /**
     * Class: jaxon.ajax.message
     */
    ajax.message = {
        /**
         * Print a success message on the screen.
         *
         * @param {string} content The message content.
         * @param {string} title The message title.
         *
         * @returns {void}
         */
        success: (content, title) => alert(content),

        /**
         * Print an info message on the screen.
         *
         * @param {string} content The message content.
         * @param {string} title The message title.
         *
         * @returns {void}
         */
        info: (content, title) => alert(content),

        /**
         * Print a warning message on the screen.
         *
         * @param {string} content The message content.
         * @param {string} title The message title.
         *
         * @returns {void}
         */
        warning: (content, title) => alert(content),

        /**
         * Print an error message on the screen.
         *
         * @param {string} content The message content.
         * @param {string} title The message title.
         *
         * @returns {void}
         */
        error: (content, title) => alert(content),

        /**
         * Ask a confirm question to the user.
         *
         * @param {string} question The confirm question.
         * @param {string} title The confirm title.
         * @param {callable} yesCallback The function to call if the user answers yesn.
         * @param {callable} noCallback The function to call if the user answers no.
         *
         * @returns {void}
         */
        confirm: (question, title, yesCallback, noCallback) => {
            if(confirm(question)) {
                yesCallback();
                return;
            }
            noCallback && noCallback();
        },
    };
})(jaxon.register, jaxon.cmd, jaxon.ajax);


module.exports = jaxon;
