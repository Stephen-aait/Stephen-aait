controllersModule.controller("AddTextController", ["$scope", "webServices", "$http", function ($scope, webServices, $http) {
    $scope.loadToolFonts = function () {
        if (fontCounter < fontsData.length) {
            for (var i = 0; i < fontsData[fontCounter].fontTtf.length; i++) {
                var fontPath = fontsData[fontCounter].fontTtf[i].fontDiff == "normal" ? normalTtf : fontsData[fontCounter].fontTtf[i].fontDiff == "bold" ? boldTtf : fontsData[fontCounter].fontTtf[i].fontDiff == "italic" ? italicTtf : boldItalicTtf;
                var t = document.createTextNode("@font-face {font-family:\"" + fontsData[fontCounter].fontTtf[i].fontTitle + "\";src:url(" + (fontPath + fontsData[fontCounter].fontTtf[i].ttf) + ");}");
                fontStyle.appendChild(t);
            }
            fontCounter++;
            $scope.loadToolFonts();
        } else {
            document.head.appendChild(fontStyle);
            $scope.loadTextTTF();
        }
    };
    $scope.loadTextTTF = function () {
        for (var fontCounter = 0; fontCounter < fontsData.length; fontCounter++) {
            for (var i = 0; i < fontsData[fontCounter].fontTtf.length; i++) {
                var img = new fabric.Text("Text Fonts", {
                    name: "Text",
                    fontFamily: fontsData[fontCounter].fontTtf[i].fontTitle,
                    text: "Text Fonts",
                    fontSize: 40
                });
                img.setCoords();
                outputCan.add(img);
                outputCan.renderAll();
            }
        }
    };
    $scope.addText = function () {
        if (nextCounter != 0 && nextCounter != canvasViewArray.length / 2) {
            nextCounter = 0;
            currentView = 0;
            angular.element(".canvas-case-inner div.canvas-container").css({
                display: "none"
            });
            var arr = angular.element(".canvas-case-inner div.canvas-container");
            angular.element(arr[nextCounter]).css({
                display: "block"
            });
        }
        canvas = canvasViewArray[currentView].canvas;
        selectedCanvas = canvas;
        var textVal = angular.element("#textArea").val();
        shapeType = "Text";
        textAlign = "left";
        angular.forEach(textColors, function (obj, i) {
            if (obj.colorCode == textColor) {
                textColorSelectedIndx = i;
            }
        });
        angular.element(".color_text ul li a").removeClass("active");
        var childs = angular.element(".color_text ul li a");
        angular.element(childs[textColorSelectedIndx]).attr("class", "active");
        var minPrice = 0;
        var printingType = angular.element(".printing-select div.font-family div.sbHolder a.sbSelector").text();
        var printingCost = 0;
        var maxTextCharacter = 3;
        angular.forEach(printings, function (costobj) {
            if (costobj.title == printingType) {				
                minPrice = parseFloat(costobj.minPrice);
                printingCost = parseFloat(costobj.cost);
                maxTextCharacter = parseInt(costobj.maxCharacter);
                
                /* Color Plattee should have only Black Color When using `Laser Engraving` printing Type START */
                if(printingType=='Laser Engraving'){
					textColor='000000';
				}
				/*END*/
            }
        });
        selectedFontSize = fabricFontSize;
        var img = new fabric.Text(textVal, {
            name: "Text",
            fontFamily: selectedTextFont,
            shapeType: shapeType,
            text: textVal,
            colorPrice: textColorPrice,
            hexColorCode: textColor,
            fill: "#" + textColor,
            fontTitle: fontTitle,
            colorTitle: textColorTitle,
            opacity: 0,
            printingType: printingType,
            printingCost: printingCost,
            minPrice: minPrice,
            maxTextCharacter: maxTextCharacter,
            colorId: textColorId,
            boldClick: boldClick,
            italicClick: italicClick,
            artColorSelectedIndx: textColorSelectedIndx,
            selectedTextFont: selectedTextFont,
            fontSize: selectedFontSize,
            selectedFontSize: selectedFontSize,
            selectedFontIndx: selectedFontIndx,
            selectedFontSizeIndx: selectedFontSizeIndx,
            textAlign: textAlign
        });
        global_SelectedItem = img;
        var nW = img.getWidth();
        var nH = img.getHeight();
        var left = (canWidth - img.getWidth()) / 2;
        var top = (canHeight - img.getHeight()) / 2;
        img.set({
            left: left + nW / 2,
            top: top + nH / 2,
            originalTextFontWidth: nW,
            originalTextFontHeight: nH,
            originX: "center",
            originY: "center"
        });
        img.setCoords();
        canvas.add(img);
        canvas.renderAll();
        canvas.calcOffset();
        setTimeout(function () {
            img.setCoords();
            canvas.renderAll();
            canvas.calcOffset();
            canvas.renderAll();
            setTimeout(function () {
                img.set({
                    fontFamily: selectedTextFont,
                    opacity: 1
                });
                img.setCoords();
                var nW = img.getWidth();
                var nH = img.getHeight();
                img.set({
                    originalTextFontWidth: nW,
                    originalTextFontHeight: nH
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
                $scope.calculateTotalPrice();
                if (mobileMode) {
                    $(".tab-content").css({
                        display: "none"
                    });
                }
            }, 100);
        }, 100);
    };
    $scope.changePrinting = function (indx) {
        var printingType = printings[indx].title;
        var maxTextCharacter = printings[indx].maxCharacter;
        var printingCost = printings[indx].cost;
        var minPrice = printings[indx].minPrice;
        var textPrintImage = textPrintingThumb + printings[indx].filename;
        angular.element(".printing-text-image").attr("src", textPrintImage);
        angular.element(".lightbox").fadeIn(300);
        angular.element("#printing-check-php h3.printing-label").text(printingType);
        angular.element("#printing-check-php").fadeIn(300);
        if (selectedCanvas) {
            var get_Obj = selectedCanvas.getActiveObject();
        }
        if (get_Obj && get_Obj.get("shapeType") == "Text") {
            get_Obj.set({
                printingType: printingType,
                printingCost: printingCost,
                minPrice: minPrice,
                maxTextCharacter: maxTextCharacter
            });
            get_Obj.setCoords();
            selectedCanvas.renderAll();
            $scope.calculateTotalPrice();
        }
        
        /* Color Plattee should have only Black Color When using `Laser Engraving` printing Type START*/
        if(indx==1){			
			angular.element("#textsview div.color_text ul li").addClass("printing-check-php");			
			var childs = angular.element(".color_text ul li");
			angular.element(childs[7]).removeClass("printing-check-php");
			angular.element(childs[7]).trigger( "click" );
		} else {
			angular.element("#textsview div.color_text ul li").removeClass("printing-check-php");
		}
		/*END*/
    };
    $scope.updateText = function () {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (selectedCanvas) {
            var get_Obj = selectedCanvas.getActiveObject();
        }
        if (get_Obj && get_Obj.get("shapeType") == "Text") {
            get_Obj.set({
                text: angular.element("textArea").val()
            });
            get_Obj.setCoords();
            selectedCanvas.renderAll();
            $scope.calculateTotalPrice();
        }
    };
    $scope.changeTextColor = function (color, indx, colorPrice, colorTitle, colorId) {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (selectedCanvas) {
            var getObj = selectedCanvas.getActiveObject();
        }
        if (getObj && getObj.get("shapeType") == "Text") {
            textColorPrice = colorPrice;
            textColorSelectedIndx = indx;
            textColorTitle = colorTitle;
            textColorId = colorId;
            textColor = color;
            angular.element(".color_text ul li a").removeClass("active");
            var childs = angular.element(".color_text ul li a");
            angular.element(childs[textColorSelectedIndx]).attr("class", "active");
            getObj.set({
                fill: "#" + color,
                hexColorCode: color,
                artColorSelectedIndx: textColorSelectedIndx,
                colorTitle: textColorTitle,
                colorId: textColorId,
                colorPrice: textColorPrice
            });
            selectedCanvas.renderAll();
            $scope.calculateTotalPrice();
            if (mobileMode) {
                $(".tab-content").css({
                    display: "none"
                });
            }
        }
    };
    $scope.setTextAlign = function (str) {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (selectedCanvas) {
            var getObj = selectedCanvas.getActiveObject();
        }
        if (getObj && getObj.get("shapeType") == "Text") {
            textAlign = str;
            getObj.set({
                textAlign: textAlign
            });
            selectedCanvas.renderAll();
            if (mobileMode) {
                $(".tab-content").css({
                    display: "none"
                });
            }
        }
    };
    $scope.changeTextFontAndSize = function (str) {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (selectedCanvas) {
            var getObj = selectedCanvas.getActiveObject();
        }
        if (getObj && getObj.get("shapeType") == "Text") {
            if (str == "font") {
                getObj.set({
                    fontFamily: selectedTextFont,
                    selectedTextFont: selectedTextFont,
                    selectedFontIndx: selectedFontIndx,
                    boldClick: boldClick,
                    fontTitle: fontTitle,
                    italicClick: italicClick
                });
                selectedCanvas.renderAll();
                setTimeout(function () {
                    getObj.set({
                        fontFamily: selectedTextFont
                    });
                    getObj.setCoords();
                    selectedCanvas.renderAll();
                    selectedCanvas.calcOffset();
                    setTimeout(function () {
                        getObj.set({
                            fontFamily: selectedTextFont
                        });
                        getObj.setCoords();
                        selectedCanvas.renderAll();
                        selectedCanvas.calcOffset();
                        saveState(getObj, CHANGETYPE_UPDATE);
                        if (mobileMode) {
                            $(".tab-content").css({
                                display: "none"
                            });
                        }
                    }, 200);
                }, 200);
            } else {
                getObj.set({
                    fontSize: selectedFontSize,
                    selectedFontSizeIndx: selectedFontSizeIndx
                });
                selectedCanvas.renderAll();
            }
        }
    };
    $scope.changeTextWeight = function (str) {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        var fontWeight = str;
        if (selectedCanvas) {
            var getObj = selectedCanvas.getActiveObject();
        }
        if (getObj && getObj.get("shapeType") == "Text") {
            if (str == "underline") {
                var textDec = getObj.get("textDecoration") == "underline" ? "none" : "underline";
                getObj.set({
                    textDecoration: textDec
                });
                selectedCanvas.renderAll();
                saveState(getObj, CHANGETYPE_UPDATE);
                if (mobileMode) {
                    $(".tab-content").css({
                        display: "none"
                    });
                }
            } else {
                $scope.findFontFamily(str);
            }
        }
    };
    $scope.findFontFamily = function (str) {
        if (currentView == 0) {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (currentView == canvasViewArray.length / 2) {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (str == "bold") {
            boldClick = boldClick == false ? true : false;
        } else if (str == "italic") {
            italicClick = italicClick == false ? true : false;
        }
        if (boldClick == true && italicClick == true) {
            var fontStyle = "bolditalic";
        } else if (boldClick == true && italicClick == false) {
            var fontStyle = "bold";
        } else if (boldClick == false && italicClick == true) {
            var fontStyle = "italic";
        } else if (boldClick == false && italicClick == false) {
            var fontStyle = "normal";
        }
        angular.forEach(fontsData[selectedFontIndx].fontTtf, function (font) {
            if (font.fontDiff == fontStyle) {
                selectedTextFont = font.fontTitle;
            }
        });
        if (selectedCanvas) {
            var getObj = selectedCanvas.getActiveObject();
        }
        if (getObj && getObj.get("shapeType") == "Text") {
            selectedCanvas.renderAll();
        }
        if (selectedTextFont) {
            $scope.changeTextFontAndSize("font");
        }
        if (mobileMode) {
            $(".tab-content").css({
                display: "none"
            });
        }
    };
    $scope.updateFonts = function (data) {
        fontsData = data.fonts;
        fontsSize = data.fontSize;
        var thumb = "";
        angular.forEach(fontsData, function (data, i) {
            if (fontTitle == "") {
                fontTitle = data.title;
            }
            angular.forEach(data.fontTtf, function (fontt, j) {
                if (selectedTextFont == "") {
                    selectedTextFont = fontt.fontTitle;
                    selectedFontIndx = i;
                }
            });
            thumb += "<option class=\"fonts\" onclick=\"changeCliparts(this)\" value=\"fonts\" data-image='" + fontImagePath + data.imagePath +"'></option>";//data.title
        });
        angular.element(".country_id2").html(thumb);
        $(".country_id2").selectbox();
        var thumb = "";
        angular.forEach(fontsSize, function (data, i) {
            if (selectedFontSize == "") {
                selectedFontSize = data.fontSize;
                selectedFontSizeIndx = i;
            }
            thumb += "<option class=\"fonts\" onclick=\"changeCliparts(this)\" value=\"fontSize\">" + data.fontSize + "px</option>";
        });
        angular.element(".country_id1").html(thumb);
        $(".country_id1").selectbox();
        $scope.loadToolFonts();
    };
    $scope.updateTextColors = function (data) {
        textColors = data.colors;
        textColor = textColors[0].colorCode;
        textColorId = textColors[0].id;
        textColorPrice = textColors[0].price;
        textColorTitle = textColors[0].title;
        $scope.textColors = data.colors;
    };
}]);
