/*
FullCalendar React Component v4.4.2
Docs: https://fullcalendar.io/docs/react
License: MIT
*/
import deepEqual from 'fast-deep-equal';
import { createElement, createRef, Component } from 'react';
import { Calendar } from '@fullcalendar/core';

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

var FullCalendar = /** @class */ (function (_super) {
    __extends(FullCalendar, _super);
    function FullCalendar() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.elRef = createRef();
        return _this;
    }
    FullCalendar.prototype.render = function () {
        return (createElement("div", { ref: this.elRef }));
    };
    FullCalendar.prototype.componentDidMount = function () {
        this.calendar = new Calendar(this.elRef.current, this.props);
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
            if (!deepEqual(props[propName], oldProps[propName])) {
                updates[propName] = props[propName];
            }
        }
        this.calendar.mutateOptions(updates, removals, false, deepEqual);
    };
    FullCalendar.prototype.componentWillUnmount = function () {
        this.calendar.destroy();
    };
    FullCalendar.prototype.getApi = function () {
        return this.calendar;
    };
    return FullCalendar;
}(Component));

export default FullCalendar;
