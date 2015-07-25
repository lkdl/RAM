define(function(require, exports, module) {
"use strict";

var oop = require("../lib/oop");
var TextHighlightRules = require("./text_highlight_rules").TextHighlightRules;

var RamHighlightRules = function() {

    var keywords = "LOAD|STORE|ADD|SUB|MULT|DIV|GOTO|END|IF";


    var keywordMapper = this.createKeywordMapper({
        "keyword": keywords
    }, "identifier", true);

    this.$rules = {
        "start" : [ {
            token : "comment",
            regex : "--.*$"
        }, {
            token : "variable",
            regex : "\\s[+-]?\\d+| A"
        }, {
            token : "constant.numeric",
            regex : "#[+-]?\\d+"
        }, {
            token : "variable.parameter",
            regex : "@\\d+"
        }, {
            token : keywordMapper,
            regex : "[a-zA-Z_$][a-zA-Z0-9_$]*\\b"
        }, {
            token : "keyword.operator",
            regex : "<|>|=|!=|<=|>="
        }]
    };
};

oop.inherits(RamHighlightRules, TextHighlightRules);

exports.RamHighlightRules = RamHighlightRules;
});
