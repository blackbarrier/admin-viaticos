/*
FullCalendar React Component v4.4.2
Docs: https://fullcalendar.io/docs/react
License: MIT
*/
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exports, require('react'), require('@fullcalendar/core')) :
    typeof define === 'function' && define.amd ? define(['exports', 'react', '@fullcalendar/core'], factory) :
    (global = global || self, factory(global.FullCalendarReact = {}, global.React, global.FullCalendar));
}(this, (function (exports, React, core) { 'use strict';

    /*! *****************************************************************************
    Copyright (c) Microsoft Corporation.

    Permission to use, copy, modify, and/or distribute this software for any
    purpose with or without fee is hereby granted.

    THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH
    REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT,
    INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM
    LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
    OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR
    PERFORMANCE OF THIS SOFTWARE.
    ***************************************************************************** */
    /* global Reflect, Promise */

    var extendStatics = function(d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };

    function __extends(d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    }

    var isArray = Array.isArray;
    var keyList = Object.keys;
    var hasProp = Object.prototype.hasOwnProperty;

    var fastDeepEqual = function equal(a, b) {
      if (a === b) return true;

      if (a && b && typeof a == 'object' && typeof b == 'object') {
        var arrA = isArray(a)
          , arrB = isArray(b)
          , i
          , length
          , key;

        if (arrA && arrB) {
          length = a.length;
          if (length != b.length) return false;
          for (i = length; i-- !== 0;)
            if (!equal(a[i], b[i])) return false;
          return true;
        }

        if (arrA != arrB) return false;

        var dateA = a instanceof Date
          , dateB = b instanceof Date;
        if (dateA != dateB) return false;
        if (dateA && dateB) return a.getTime() == b.getTime();

        var regexpA = a instanceof RegExp
          , regexpB = b instanceof RegExp;
        if (regexpA != regexpB) return false;
        if (regexpA && regexpB) return a.toString() == b.toString();

        var keys = keyList(a);
        length = keys.length;

        if (length !== keyList(b).length)
          return false;

        for (i = length; i-- !== 0;)
          if (!hasProp.call(b, keys[i])) return false;

        for (i = length; i-- !== 0;) {
          key = keys[i];
          if (!equal(a[key], b[key])) return false;
        }

        return true;
      }

      return a!==a && b!==b;
    };

    var FullCalendar = /** @class */ (function (_super) {
        __extends(FullCalendar, _super);
        function FullCalendar() {
            var _this = _super !== null && _super.apply(this, arguments) || this;
            _this.elRef = React.createRef();
            return _this;
        }
        FullCalendar.prototype.render = function () {
            return (React.createElement("div", { ref: this.elRef }));
        };
        FullCalendar.prototype.componentDidMount = function () {
            this.calendar = new core.Calendar(this.elRef.current, this.props);
            this.calendar.render();
        };
        FullCalendar.prototype.componentDidUpdate = function (oldProps) {
            var props = this.props;
            var updates = {};
            var removals = [];
            for (var propName in oldProps) {
                if (!(propName in props)) {
                    removals.push(propName);
                }
            }
            /*
            Do a deep-comparison for prop changes. We do this because often times the parent component will pass in
            an object literal that generates a new reference every time its render() function runs.
            This isn't too much of a performance hit because normally these object literals are rather small.
            For larger data, the parent component will almost definitely generate a new reference on every mutation,
            because immutable prop data is the norm in React-world, making the deepEqual function execute really fast.
            */
            for (var propName in props) {
                if (!fastDeepEqual(props[propName], oldProps[propName])) {
                    updates[propName] = props[propName];
                }
            }
            this.calendar.mutateOptions(updates, removals, false, fastDeepEqual);
        };
        FullCalendar.prototype.componentWillUnmount = function () {
            this.calendar.destroy();
        };
        FullCalendar.prototype.getApi = function () {
            return this.calendar;
        };
        return FullCalendar;
    }(React.Component));

    exports.default = FullCalendar;

    Object.defineProperty(exports, '__esModule', { value: true });

})));
