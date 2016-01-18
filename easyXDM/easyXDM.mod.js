/*jslint evil: true */
/**
 * easyXDM
 * http://easyxdm.net/
 * Copyright(c) 2009-2011, Øyvind Sean Kinsey, oyvind@kinsey.no.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
/**
 * This just aggregates all the separate files
 */
(function(){
    var scripts = document.getElementsByTagName("script"), src = scripts[scripts.length - 1].src.replace("easyXDM.debug.js", "");
    var thisURL = 'dev.hardcover.me';
    var l = window.location;
    var base_url = l.protocol + "//" + l.host;
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/Core.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/Debug.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/DomHelper.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/Fn.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/Socket.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/Rpc.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/FlashTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/SameOriginTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/PostMessageTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/FrameElementTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/NameTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/HashTransport.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/ReliableBehavior.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/QueueBehavior.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/VerifyBehavior.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
    js2.src = l.protocol+"//"+thisURL+"/easyXDM/stack/RpcBehavior.js";
    document.body.appendChild(js2);
    var js2 = document.createElement("script");
    js2.type = "text/javascript";
}());
