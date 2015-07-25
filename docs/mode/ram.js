define(function(require, exports, module) {
"use strict";

var oop = require("../lib/oop");
var TextMode = require("./text").Mode;
var RamHighlightRules = require("./ram_highlight_rules").RamHighlightRules;
var Range = require("../range").Range;

var Mode = function() {
    this.HighlightRules = RamHighlightRules;
};
oop.inherits(Mode, TextMode);

(function() {

    this.lineCommentStart = "--";

    this.$id = "ace/mode/ram";
}).call(Mode.prototype);

exports.Mode = Mode;

});
