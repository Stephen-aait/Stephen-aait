controllersModule.controller("RootController", ["$scope", "webServices", "$http", function ($scope, webServices, $http) {
    $scope.clickBoxFlag = true;
    $scope.sitePath = sitePath;
    $scope.optionColorableFlag = true;
    $scope.clipArtPath = clipArtPath;
    $scope.sizesPath = sizes_path;
    $scope.removeSelectedFlag = false;
    $scope.uploadThumb = uploadThumb;
    $scope.totalPrice = 0;
    $scope.doubleStrapSelectionFlag = doubleStrapSelectionFlag;
    $scope.loadProducts = function () {
        webServices.customServerCall($http, siteUrl + "php/json/products.json", null, "products", $scope);
    };
    $scope.checkLicence = function () {
        $scope.loadToolData();
    };
    $scope.loadToolData = function () {
        promiseDataArrCounter = 0;
        promiseDataArr = new Array;
        promiseDataArr.push(dataAccessUrl + "designersoftware/font");
        promiseDataArr.push(dataAccessUrl + "designersoftware/text/color");
        promiseDataArr.push(dataAccessUrl + "designersoftware/angles");
        promiseDataArr.push(dataAccessUrl + "designersoftware/parts/style");
        promiseDataArr.push(dataAccessUrl + "designersoftware/sizes");
        promiseDataArr.push(dataAccessUrl + "designersoftware/text");
        promiseDataArr.push(dataAccessUrl + "designersoftware/clipart/price");
        loadDataByPromise("tooldata");
    };
    $scope.loadTextColors = function () {
        var obj = new Object;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/text/color", null, "textColors", $scope);
    };
    $scope.loadAngle = function () {
        var obj = new Object;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/angles", obj, "angles", $scope);
    };
    $scope.loadParts = function () {};
    $scope.loadParts = function (data) {
        productPartData = data;
        selectedPartPrice = productPartData[0].price;
        partTitle = productPartData[0].partsStyleName;
        groupTitle = productPartData[0].partIdenti;
        $scope.managePocketAndHandleThumb();
        if (editMode == "edit") {
            $scope.loadDataInEditMode();
        } else {
            $scope.loadPartLayersInfoByCode(productPartData[0].partsStyleId, partTitle, groupTitle, selectedPartPrice);
        }
    };
    $scope.managePocketAndHandleThumb = function () {
        if (!pocketStyleData) {
            pocketStyleData = new Array;
            handleStyleData = new Array;
            strapStyleData = new Array;
            angular.forEach(productPartData, function (part, i) {
                if (part.partIdenti == "Pocket" || part.partIdenti == "Handle") {
                    var obj = new Object;
                    obj.partsStyleId = part.partsStyleId;
                    obj.partsStyleName = part.partsStyleName;
                    obj.partsStyleCode = part.partsStyleCode;
                    obj.partIdenti = part.partIdenti;
                    obj.price = part.price;
                    part.partIdenti == "Pocket" ? pocketStyleData.push(obj) : handleStyleData.push(obj);
                }
                if (part.partIdenti == "ShoulderPad" || part.partIdenti == "Double Shoulder Pad") {
                    var obj = new Object;
                    obj.partsStyleId = part.partsStyleId;
                    obj.partsStyleName = part.partsStyleName;
                    obj.partsStyleCode = part.partsStyleCode;
                    obj.partIdenti = part.partIdenti;
                    obj.price = part.price;
                    obj.strapCheck = part.partIdenti == "ShoulderPad" ? "single-check" : "double-check";
                    obj.strap = part.partIdenti == "ShoulderPad" ? "single" : "double";
                    obj.checked = part.partIdenti == "ShoulderPad" ? true : false;
                    strapStyleData.push(obj);
                }
            });
            $scope.strapStyleData = strapStyleData;
            $scope.pocketStyleData = pocketStyleData;
            $scope.handleStyleData = handleStyleData;
        }
    };
    $scope.loadPartLayersInfoByCode = function (partsStId, partT, groupT, price) {
        if (groupT == "Pocket" || groupT == "Handle") {
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
        }
        var flag = true;
        angular.forEach(partDropDownArr, function (group, i) {
            if (group.partName == partT) {
                angular.element(".preloader, .lightbox").css({
                    display: "none"
                });
                flag = false;
                alert("You can only add styles only one times..");
            }
        });
        if (flag == true) {
            $scope.openPreloader();
            partTitle = partT;
            groupTitle = groupT;
            selectedPartPrice = price;
            partsStyleId = partsStId;
            var obj = new Object;
            obj.partsStyleId = partsStyleId;
            webServices.customServerCall($http, dataAccessUrl + "designersoftware/parts/layers", obj, "layersInfo", $scope);
        }
    };
    $scope.loadPartLayersInfoByCodeInEditCase = function (partsStId, partT, groupT, price) {
        $scope.openPreloader();
        partTitle = partT;
        groupTitle = groupT;
        selectedPartPrice = price;
        partsStyleId = partsStId;
        if (partsStyleId) {
            partsStyleId;
        }
        var obj = new Object;
        obj.partsStyleId = partsStyleId;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/parts/layers", obj, "layersInfo", $scope);
    };
    $scope.loadPartLayersByPartCode = function () {
        imageCounter = 0;
        layerCounter = 0;
        viewCounter = 0;
        imageLeft = 0;
        imageTop = 0;
        img1[0] = new Array;
        loadImagesOnCanvas2();
    };
    $scope.addOptions = function (id, thumb, colorable, price, color, label, colorPrice, colorTitle, colorId) {
        optionsSizesArr = new Array;
        colorable = 1;
        var obj = new Object;
        obj.id = id;
        obj.colorCode = color;
        obj.label = label;
        var layers = new Array;
        var layerObj = new Object;
        layerObj.id = id;
        layerObj.color = color;
        layers.push(layerObj);
        obj.layers = layers;
        optionsSizesArr.push(obj);
        $scope.optionsSizesArr = optionsSizesArr;
        $scope.addSizeOptions(id, thumb, colorable, price, color, label, colorPrice, colorTitle, colorId);
    };
    $scope.selectOptionsByName = function (optionId, layers, label, colorCode) {
        $scope.openPreloader("Loading Design Colors");
        selectedCanvas = canvasViewArray[0].canvas;
        selectedCanvas.forEachObject(function (object) {
            if (object.optionId == optionId) {
                selectedCanvas.setActiveObject(object);
                selectedCanvas.renderAll();
            }
        });
        selectedPartInfoObj = layers;
        angular.element(".selected-part-title").text(label);
        $scope.selectedPartInfoObj = null;
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
        $scope.removeSelectedFlag = false;
        var obj = new Object;
        obj.sizesId = optionId;
        $scope.layerColors = null;
        if (colorCode) {
            webServices.customServerCall($http, dataAccessUrl + "designersoftware/sizes/color", obj, "optionLayerColors", $scope);
        } else {
            $scope.optionLayerColors = null;
            if (!$scope.$$phase) {
                $scope.$apply();
            }
            $scope.hidePopUp("preloader");
        }
    };
    $scope.changeOptionColor = function (color, price, colorTitle, colorId) {
        angular.element(".color-texture-holder").css({
            display: "none"
        });
        var getObj = selectedCanvas.getActiveObject();
        if (getObj && getObj.get("shapeType") == "Option") {
            var arr = hex2Rgb("0x" + color);
            global_SelectedItem = getObj;
            colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
            global_SelectedItem.set({
                hexColorCode: color,
                colorPrice: price,
                colorId: colorId,
                colorTitle: colorTitle
            });
            global_SelectedItem.fill = colorPickerValue;
            colorPickerValue = colorPickerValue;
            global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
            global_SelectedItem.applyFilters(selectedCanvas.renderAll.bind(selectedCanvas));
            $scope.calculateTotalPrice();
        }
    };
    $scope.addSizeOptions = function (id, thumb, colorable, price, color, label, colorPrice, colorTitle, colorId) {
        $scope.openPreloader();
        if (selectedSizeObj) {
            var can = canvasViewArray[0].canvas;
            can.remove(selectedSizeObj);
        }
        var httpUrl = sizes_path + thumb;
        fabric.Image.fromURL(httpUrl, function (img) {
            var cW = 68;
            var cH = 66;
            var oW = img.width;
            var oH = img.height;
            var cR = cW / cH;
            var oR = oW / oH;
            var nW = 20;
            var nH = 20;
            if (cR > oR) {
                nH = cH;
                nW = oW * nH / oH;
            } else {
                nW = cW;
                nH = nW * oH / oW;
            }
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
            var left = 18;
            var top = 60;
            canvas = canvasViewArray[currentView].canvas;
            selectedCanvas = canvas;
            img.set({
                name: "Option",
                left: left,
                width: nW,
                optionId: id,
                height: nH,
                top: top,
                shapeType: "Option",
                price: price,
                objTitle: label,
                colorTitle: colorTitle,
                colorPrice: colorPrice,
                hasBorders: false,
                selectable: true,
                lockMovementX: true,
                lockMovementY: true,
                colorable: colorable,
                thumbSrc: thumb
            });
            img.setControlVisible("tr", false);
            img.setControlVisible("br", false);
            img.setControlVisible("bl", false);
            img.setControlVisible("tl", true);
            img.setControlVisible("mr", false);
            img.setControlVisible("mt", false);
            img.setControlVisible("mb", false);
            img.setControlVisible("ml", false);
            if (color) {
                $scope.optionColorableFlag = true;
                global_SelectedItem = img;
                var arr = hex2Rgb("0x" + color);
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color,
                    colorId: colorId
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(selectedCanvas.renderAll.bind(selectedCanvas));
            } else {
                $scope.optionColorableFlag = false;
            }
            img.setCoords();
            selectedCanvas.renderAll();
            selectedSizeObj = img;
            canvas.add(img);
            canvas.renderAll();
            saveState(img, CHANGETYPE_ADD);
            if ($scope.selectedPartInfoObj == null) {
                $scope.selectOptionsByName(id, "", label, color);
                canvas.setActiveObject(img);
                canvas.renderAll();
            }
            setTimeout(function () {
                var cloneObj = fabric.util.object.clone(img);
                canvas.setActiveObject(img);
                canvas.renderAll();
                $scope.calculateTotalPrice();
                $scope.hidePopUp("preloader");
                $scope.removeSelectedFlag = false;
                if (!mobileMode) {
                    $scope.changeView(1, true);
                }
                if (angular.element(".selected-part-title").text() != img.objTitle) {
                    $scope.openPreloader("Loading Design Colors");
                    $scope.selectOptionsByName(img.get("optionId"), "", img.objTitle, img.get("hexColorCode"));
                }
            }, 200);
            if (mobileMode) {
                $(".tab-content").css({
                    display: "none"
                });
            }
        });
    };
    $scope.hideClickBox = function (obj) {
        $scope.clickBoxFlag = false;
        $scope.showRotation();
    };
    $scope.checkLicence();
    $scope.changeView = function (index, objClick) {
        if (objClick == true) {
            if (selectedCanvas) {
                var getObj = selectedCanvas.getActiveObject();
            }
        } else {}
        angular.element(".tab-content").css({
            display: "block"
        });
        angular.element(".nav-tabs li").removeClass("active");
        angular.element(".nav-tabs li").eq(index).addClass("active");
        angular.element(".tab-pane").css({
            display: "none"
        });
        angular.element(".upload_photo_box").css({
            display: "none"
        });
        angular.element(".art_upload_section").css({
            display: "none"
        });
        angular.element(".art-view-section").css({
            display: "block"
        });
        var indx = parseInt(index);
        if (indx == 0) {
            angular.element("#stylesview").css({
                display: "block"
            });
        } else if (indx == 1) {
            angular.element("#partsview").css({
                display: "block"
            });
        } else if (indx == 2) {
            if (!clipartsCat) {
                angular.element(".art_select").css({
                    display: "none"
                });
                $scope.openPreloader("Loading Clipart Data");
                promiseDataArrCounter = 0;
                loadDataByPromise("cliparts");
            }
            angular.element("#clipartsview").css({
                display: "block"
            });
            if (objClick == true) {
                if (getObj) {
                    if (getObj.get("shapeType") == "UploadImage") {
                        angular.element(".upload_photo_box").css({
                            display: "block"
                        });
                        angular.element(".art_upload_section").css({
                            display: "none"
                        });
                        angular.element(".art-view-section").css({
                            display: "none"
                        });
                    }
                }
            }
        } else if (indx == 3) {
            angular.element("#textsview").css({
                display: "block"
            });
        }
    };
    $scope.fetchUploadData = function () {
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/upload/images", null, "uploadData", $scope);
    };
    $scope.makeCanvasObjcts = function () {
        canvasViewArray = new Array;
        var left = (angular.element(".canvas-case-inner").width() - canWidth) / 2;
        var top = (angular.element(".canvas-case-inner").height() - canHeight) / 2;
        angular.forEach(anglesData, function (angle, i) {
            var thumb = "<canvas id=\"canvas_" + i + "\" width=\"" + canWidth + "\" height=\"" + canHeight + "\" style=\"position:absolute;left:0px;top:0px;border:1px none gray\"></canvas>";
            $(".canvas-case-inner").append(thumb);
            var canId = "canvas_" + i;
            var canvas = new fabric.Canvas(canId);
            canvas.selection = false;
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                fabric.Object.prototype.cornerSize = 42;
            }
            canvasViewArray.push({
                canvasId: canId,
                canvas: canvas
            });
            canvas.observe("mouse:down", function () {
                canvasSelectionFlag = "design";
                canvas = canvasViewArray[nextCounter].canvas;
                selectedCanvas = canvas;
                var get_Obj = canvas.getActiveObject();
                if (get_Obj) {
                    objPreventFlag = true;
                    if (get_Obj.shapeType == "Clipart") {
                        if (!mobileMode) {
                            $scope.changeView(2, true);
                        }
                        var colorable = get_Obj.get("colorable");
                        if (colorable == 1) {
                            var sc = angular.element("#clipartsview").scope();
                            sc.clipartcolorableFlag = true;
                            sc.$apply();
                            clipColorPrice = get_Obj.get("colorPrice");
                            artColorSelectedIndx = get_Obj.get("artColorSelectedIndx");
                            defaultClipColor = get_Obj.get("hexColorCode");
                            clipColorTitle = get_Obj.get("colorTitle");
                            clipColorId = get_Obj.get("colorId");
                            angular.element(".art-color ul li a").removeClass("active");
                            var childs = angular.element(".art-color ul li a");
                            angular.element(childs[artColorSelectedIndx]).attr("class", "active");
                        } else {
                            var sc = angular.element("#clipartsview").scope();
                            sc.clipartcolorableFlag = false;
                            sc.$apply();
                            angular.element(".art-color ul li a").removeClass("active");
                        }
                    } else if (get_Obj.shapeType == "Text") {
                        if (!mobileMode) {
                            $scope.changeView(3, true);
                        }
                        textColorPrice = get_Obj.get("colorPrice");
                        textColorTitle = get_Obj.get("colorTitle");
                        textColorId = get_Obj.get("colorId");
                        angular.element("#textArea").val(get_Obj.get("text"));
                        textColorSelectedIndx = get_Obj.get("artColorSelectedIndx");
                        angular.element(".font-property-box div.color_text ul li a").removeClass("active");
                        var childs = angular.element(".font-property-box div.color_text ul li a");
                        angular.element(childs[textColorSelectedIndx]).attr("class", "active");
                        boldClick = get_Obj.get("boldClick");
                        italicClick = get_Obj.get("italicClick");
                        angular.element(".font-property-box div.font-family div.sbHolder a.sbSelector").text(get_Obj.get("fontTitle"));
                        angular.element(".printing-select div.font-family div.sbHolder a.sbSelector").text(get_Obj.get("printingType"));
                    } else if (get_Obj.shapeType == "UploadImage") {
                        if (!mobileMode) {
                            $scope.changeView(2, true);
                        }
                    } else if (get_Obj.shapeType == "Option") {
                        $scope.removeSelectedFlag = false;
                        if (!mobileMode) {
                            $scope.changeView(1, true);
                        }
                        if (angular.element(".selected-part-title").text() != get_Obj.objTitle) {
                            $scope.openPreloader("Loading Design Colors");
                            $scope.selectOptionsByName(get_Obj.get("optionId"), "", get_Obj.objTitle, get_Obj.get("hexColorCode"));
                        }
                    } else {
                        angular.element("#textArea").val("");
                        $scope.optionColorableFlag = true;
                        if (get_Obj.groupTitle == "Pocket" || get_Obj.groupTitle == "Handle" || get_Obj.groupTitle == "ShoulderPad" || get_Obj.groupTitle == "Double Shoulder Pad") {
                            $scope.removeSelectedFlag = true;
                        } else {
                            $scope.removeSelectedFlag = false;
                        }
                        if (!mobileMode) {
                            $scope.changeView(1, true);
                        }
                        angular.forEach(partDropDownArr, function (group, i) {
                            if (group.partName == get_Obj.partName) {
                                selectedPartInfoObj = group.layers;
                                selectedPartIndx = i;
                                $scope.selectedPartInfoObj = selectedPartInfoObj;
                                $scope.$apply();
                                if (angular.element(".selected-part-title").text() != group.partName) {
                                    $scope.openPreloader("Loading Design Colors");
                                    $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId);
                                }
                            }
                        });
                    }
                } else {
                    objPreventFlag = false;
                    angular.element("#textArea").val("");
                }
            });
            canvas.observe("object:removed", function () {
                $scope.calculateTotalPrice();
            });
            canvas.observe("object:scaling", function () {
                var getObj = canvas.getActiveObject();
                if (getObj) {
                    var rootSc = angular.element(".root-controller").scope();
                    rootSc.checkLidBounding();
                    rootSc.calculateTotalPrice();
                }
            });
            canvas.observe("mouse:up", function () {
                objPreventFlag = false;
                canvas = canvasViewArray[nextCounter].canvas;
                var getObj = canvas.getActiveObject();
                if (getObj) {
                    if (getObj.get("shapeType") == "Text" || getObj.get("shapeType") == "Clipart" || getObj.get("shapeType") == "UploadImage") {
                        getObj.setCoords();
                        canvas.renderAll();
                        var rotationObj = getObj.get("oCoords").tl;
                        var boundingRect = getObj.getBoundingRect();
                        var obj1 = new Object;
                        obj1.x = -(boundingRect.width - 20);
                        obj1.y = -(boundingRect.height - 20);
                        var obj2 = new Object;
                        obj2.x = canWidth + (boundingRect.width - 20);
                        obj2.y = canHeight + (boundingRect.height - 20);
                        var cloneObj = fabric.util.object.clone(getObj);
                        var withinFlag = cloneObj.isContainedWithinRect(obj1, obj2);
                        if (withinFlag == false) {
                            if (getObj.get("shapeType") == "Text") {
                                var left = canWidth / 2;
                                var top = canHeight / 2;
                            } else {
                                var left = (canWidth - boundingRect.width) / 2;
                                var top = (canHeight - boundingRect.height) / 2;
                            }
                            getObj.set({
                                left: left,
                                top: top
                            });
                            getObj.setCoords();
                            canvas.renderAll();
                            canvas.calcOffset();
                        } else {}
                    }
                    if (getObj.groupTitle == "Pocket") {
                        getObj.setCoords();
                        canvas.renderAll();
                        var rotationObj = getObj.get("oCoords").tl;
                        var boundingRect = getObj.getBoundingRect();
                        var obj1 = new Object;
                        obj1.x = -(boundingRect.width - 20);
                        obj1.y = -(boundingRect.height - 20);
                        var obj2 = new Object;
                        obj2.x = canWidth + (boundingRect.width - 20);
                        obj2.y = canHeight + (boundingRect.height - 20);
                        var cloneObj = fabric.util.object.clone(getObj);
                        var withinFlag = cloneObj.isContainedWithinRect(obj1, obj2);
                        if (withinFlag == false) {
                            var left = (canWidth - boundingRect.width) / 2;
                            var top = (canHeight - boundingRect.height) / 2;
                            getObj.set({
                                left: left,
                                top: top
                            });
                            getObj.setCoords();
                            canvas.renderAll();
                            canvas.calcOffset();
                        } else {
                            var left = getObj.getLeft();
                            var top = getObj.getTop();
                            partDropDownArr[selectedPartIndx].layers[0].left = left;
                            partDropDownArr[selectedPartIndx].layers[0].top = top;
                            angular.forEach(canvasViewArray, function (canobj, i) {
                                canvas = canobj.canvas;
                                var objects = canvas.getObjects();
                                for (var j = 0; j < objects.length; j++) {
                                    if (objects[j].get("partName") == getObj.get("partName")) {
                                        global_SelectedItem = objects[j];
                                        global_SelectedItem.set({
                                            left: left,
                                            top: top
                                        });
                                        global_SelectedItem.setCoords();
                                        canvas.renderAll();
                                        break;
                                    }
                                }
                            });
                        }
                    }
                }
                var rootSc = angular.element(".root-controller").scope();
                rootSc.checkLidBounding();
                rootSc.calculateTotalPrice();
            });
        });
        var childs = angular.element(".canvas-case-inner div.canvas-container");
        angular.element(".canvas-case-inner div.canvas-container").css({
            display: "none",
            top: top + "px",
            left: left + "px"
        });
        $(".canvas-case-inner div.canvas-container").attr("ondragover", "allowDrop(event)");
        $(".canvas-case-inner div.canvas-container").attr("ondrop", "drop(event)");
        angular.element(childs[0]).css({
            display: "block"
        });
    };
    $scope.loadDataInEditMode = function () {
        var obj = new Object;
        obj.designId = designId;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/product", obj, "editData", $scope);
    };
    $scope.manageDataInEditMode = function () {
        editMode = "edit";
        editCounter = 0;
        setUndoRedo();
        angular.forEach(canvasViewArray, function (canObj, i) {
            canvas = canObj.canvas;
            canvas.clear();
            canvas.renderAll();
        });
        $scope.manageLayersInEditMode();
    };
    $scope.manageLayersInEditMode = function () {
        if (editCounter < partDropDownArr.length) {
            var part = partDropDownArr[editCounter];
            editSelectedPartLayersInfo = part.layers;
            if (part.groupTitle == "Double Shoulder Pad") {
                strapSelectedIndx = 1;
            } else if (part.groupTitle == "ShoulderPad") {
                strapSelectedIndx = 0;
            }
            $scope.loadPartLayersInfoByCodeInEditCase(part.partsStyleId, part.partName, part.groupTitle, part.selectedPartPrice);
        } else {
            previewCounter = 0;
            $scope.loadArtInDesignCanvas();
        }
    };
    $scope.loadImagesOnCanvas = function () {
        if (viewCounter < anglesData.length) {
            if (layerCounter < selectedPartLayersInfo.layers.length) {
                var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[viewCounter].name + ".png";
                fabric.Image.fromURL(imageSrc, function (img) {
                    var cW = 235;
                    var cH = 440;
                    var oW = img.width;
                    var oH = img.height;
                    var cR = cW / cH;
                    var oR = oW / oH;
                    var nW = 20;
                    var nH = 20;
                    if (cR > oR) {
                        nH = cH;
                        nW = oW * nH / oH;
                    } else {
                        nW = cW;
                        nH = nW * oH / oW;
                    }
                    if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                        var width = img.width;
                        var height = img.height;
                        var left = parseFloat(editSelectedPartLayersInfo[0].left);
                        var top = parseFloat(editSelectedPartLayersInfo[0].top);
                    } else {
                        var width = img.width;
                        var height = img.height;
                        var left = (canWidth - width) / 2;
                        var top = (canHeight - height) / 2;
                        partLeftPos = left;
                        partTopPos = top;
                    }
                    img1[0][layerCounter] = img.set({
                        perPixelTargetFind: true,
                        selectable: false,
                        partStyleCode: selectedPartLayersInfo.partStyleCode,
                        layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                        angleName: anglesData[viewCounter].name,
                        partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                        width: width,
                        height: height
                    });
                    if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                        var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                    } else {
                        var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                    }
                    if (color) {
                        canvas = canvasViewArray[viewCounter].canvas;
                        var arr = hex2Rgb("0x" + color);
                        global_SelectedItem = img;
                        colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                        global_SelectedItem.set({
                            hexColorCode: color
                        });
                        global_SelectedItem.fill = colorPickerValue;
                        colorPickerValue = colorPickerValue;
                        global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                        global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                    }
                    layerCounter++;
                    if (layerCounter < selectedPartLayersInfo.layers.length) {
                        $scope.loadImagesOnCanvas();
                    } else {
                        if (groupTitle == "Pocket") {
                            var lockMovementX = true;
                            var lockMovementY = false;
                        } else {
                            var lockMovementX = true;
                            var lockMovementY = true;
                        }
                        canvas = canvasViewArray[viewCounter].canvas;
                        var grpObj = new fabric.Group(img1[0], {
                            perPixelTargetFind: true,
                            shapeType: "grp",
                            lockMovementX: lockMovementX,
                            lockMovementY: lockMovementY,
                            groupTitle: groupTitle,
                            selectable: true,
                            partName: partTitle,
                            left: left,
                            top: top,
                            hasControls: false,
                            hasBorders: false
                        });
                        canvas.add(grpObj);
                        canvas.renderAll();
                        if (groupTitle == "Double Shoulder Pad") {}
                        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
                            if (editMode != "edit") {
                                saveState(grpObj, CHANGETYPE_ADD);
                            }
                        }
                        viewCounter++;
                        layerCounter = 0;
                        img1[0] = new Array;
                        $scope.loadImagesOnCanvas();
                    }
                });
            }
        } else {
            if (editMode == "edit") {
                editCounter++;
                $scope.manageLayersInEditMode();
            } else {
                $scope.manageLayersAndTitle();
            }
        }
    };
    $scope.removeOptionSizes = function (opId) {
        angular.forEach(optionsSizesArr, function (size, i) {
            if (size.id == opId) {
                optionsSizesArr.splice(i, 1);
                $scope.optionsSizesArr = optionsSizesArr;
                selectedPartIndx = 0;
                selectedPartInfoObj = partDropDownArr[0].layers;
                $scope.selectedPartInfoObj = selectedPartInfoObj;
                $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId, "", "radioSelect");
            }
        });
    };
    $scope.removeSelectedGroup = function (str, strap, removeTitle) {
        detectArr.push((new Date).getTime());
        if (detectArr.length >= 2) {} else {
            if (str) {
                var selectedPartName = str;
            } else {
                var selectedPartName = selectedPartInfoObj[0].parentName;
            }
            $scope.removeSelectedFlag = false;
            // if (selectedPartInfoObj[0]) {
            //     var groupName = selectedPartInfoObj[0].groupName;
            //     if (groupName == "ShoulderPad" || groupName == "Double Shoulder Pad") {
            //         angular.element(".single-check, .double-check").removeClass("c_on");
            //     }
            // }
            angular.forEach(partDropDownArr, function (group, i) {
                if (group.partName == selectedPartName) {
                    partDropDownArr.splice(i, 1);
                    $scope.partDropDownArr = partDropDownArr;
                    selectedPartIndx = 0;
                    selectedPartInfoObj = partDropDownArr[0].layers;
                    $scope.selectedPartInfoObj = selectedPartInfoObj;
                    $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId, "", "radioSelect");
                }
            });
            angular.forEach(canvasViewArray, function (canobj, i) {
                canvas = canobj.canvas;
                canvas.forEachObject(function (obj) {					
                    if (obj.get("partName") == selectedPartName) {
						//console.log(selectedPartName+' Removed');
						console.log(obj);						
                        canvas.remove(obj);
                        canvas.renderAll();
                        detectArr = new Array;
                        $scope.calculateTotalPrice();
                    }
                });
            });
            if (strap) {
                $scope.strapAfterDelete(strap, removeTitle);
            }
        }
    };
    $scope.strapAfterDelete = function (straps, removeTitle) {
        detectArr = new Array;
        preventFlag = false;
        if (straps == "single") {
            if (angular.element(".single-check").hasClass("c_on")) {
                otherPartLoadTitle = "ShoulderPad";
                $scope.doubleStrapSelectionFlag = false;
                $scope.loadStraps();
            }
        } else if (straps == "double") {
            if (angular.element(".double-check").hasClass("c_on")) {
                otherPartLoadTitle = "Double Shoulder Pad";
                $scope.doubleStrapSelectionFlag = true;
                $scope.loadStraps();
            }
        }
    };
    $scope.manageLayersAndTitle = function () {
        var obj = new Object;
        obj.partName = partTitle;
        obj.groupTitle = groupTitle;
        obj.partsStyleId = partsStyleId;
        obj.left = partLeftPos;
        obj.top = partTopPos;
        obj.selectedPartPrice = selectedPartPrice;
        var layers = new Array;
        for (var i = 0; i < selectedPartLayersInfo.layers.length; i++) {
            var layerObj = new Object;
            layerObj.layerId = selectedPartLayersInfo.layers[i].layerId;
            layerObj.layerTitle = selectedPartLayersInfo.layers[i].layerName;
            layerObj.layerName = selectedPartLayersInfo.layers[i].layerCode;
            layerObj.parentName = partTitle;
            layerObj.groupName = groupTitle;
            layerObj.styleCode = selectedPartLayersInfo.partStyleCode;
            layerObj.left = partLeftPos;
            layerObj.top = partTopPos;
            layerObj.colorTitle = selectedPartLayersInfo.layers[i].defaultColorTitle;
            layerObj.colorId = selectedPartLayersInfo.layers[i].defaultColorId;
            layerObj.hexColorCode = selectedPartLayersInfo.layers[i].defaultColorCode;
            if (!selectedPartLayersInfo.layers[i].defaultColorCode) {
                layerObj.layerColorPrice = 0;
            } else {
                layerObj.layerColorPrice = selectedPartLayersInfo.layers[i].price;
            }
            layers.push(layerObj);
        }
        obj.layers = layers;
        partDropDownArr.push(obj);
        $scope.partDropDownArr = partDropDownArr;
        if (mobileMode) {
            $(".tab-content").css({
                display: "none"
            });
        }
        if (!selectedPartInfoObj) {
            selectedPartInfoObj = layers;
            $scope.selectedPartInfoObj = selectedPartInfoObj;
            $scope.make3DRotation();
            $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId);
        } else {
            $scope.loadOtherLayers(otherPartLoadTitle);
        }
        $scope.$apply();
        $scope.calculateTotalPrice();
    };
    $scope.loadOtherLayers = function (str) {
        if (str != "") {
            setTimeout(function () {
                $scope.loadStraps(str);
            }, 500);
        } else {
            $scope.hidePopUp("preloader");
            if (onlyOneTimeRotFlag) {
                $scope.showRotation();
                onlyOneTimeRotFlag = false;
            }
        }
    };
    $scope.addSingleOrDoubleStrap = function (straps, removeTitle) {
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
        preventFlag = false;
        if (straps == "single") {
            if (angular.element(".single-check").hasClass("c_on")) {
                $scope.removeSelectedGroup("Backpack Setup", straps, removeTitle);
            }
        } else if (straps == "double") {
            if (angular.element(".double-check").hasClass("c_on")) {
                $scope.removeSelectedGroup("Shoulder Pad", straps, removeTitle);
            }
        }
    };
    $scope.loadStraps = function () {
        for (var i = 0; i < productPartData.length; i++) {
            var part = productPartData[i];
            if (part.partIdenti == otherPartLoadTitle) {
				console.log(otherPartLoadTitle);
                otherPartLoadTitle = "";                
                console.log('loading straps');
                $scope.loadPartLayersInfoByCode(part.partsStyleId, part.partsStyleName, part.partIdenti, part.price);
                break;
            }
        }
    };
    $scope.managePartsAndLayers = function (layerId, str, radioClick) {
        if (selectedPartInfoObj) {
            if (mobileMode) {
                if (str) {
                    $(".tab-content").css({
                        display: "block"
                    });
                } else {
                    $(".tab-content").css({
                        display: "none"
                    });
                }
            }
            angular.element(".selected-part-title").text(selectedPartInfoObj[0].parentName);
            var obj = new Object;
            obj.partsLayersId = layerId;
            webServices.customServerCall($http, dataAccessUrl + "designersoftware/parts_layers/color", obj, "layerColors", $scope, radioClick);
        }
    };
    $scope.changePartColorByIndex = function (color, price, colorTitle, colorId) {
        angular.element(".color-texture-holder").css({
            display: "none"
        });
        preColorSelectedItem = null;
        var parentName = angular.element(".part-layer-holder div.active").attr("parentName");
        var layerName = angular.element(".part-layer-holder div.active").attr("layerName");
        var indx = angular.element(".part-layer-holder div.active").parent().index();
        angular.element(".selected-part-title").text(parentName);
        selectedPartInfoObj[indx].hexColorCode = color;
        selectedPartInfoObj[indx].layerColorPrice = price;
        selectedPartInfoObj[indx].colorTitle = colorTitle;
        selectedPartInfoObj[indx].colorId = colorId;
        $scope.selectedPartInfoObj = selectedPartInfoObj;
        var grpName = selectedPartInfoObj[indx].groupName;
        var partlayerName = selectedPartInfoObj[indx].layerName;
        var styleCode = selectedPartInfoObj[indx].styleCode;
        partDropDownArr[selectedPartIndx].layers = selectedPartInfoObj;
        $scope.partDropDownArr = partDropDownArr;
        if (grpName == "Body" && (layerName == "LAYER8" || layerName == "LAYER12" || layerName == "LAYER14")) {
            $scope.manageOnlySingleStichingColor(color, price, colorTitle, colorId, grpName, styleCode);
        } else if ((grpName == "ShoulderPad" || grpName == "Double Shoulder Pad") && (layerName == "LAYER8" || layerName == "LAYER6")) {
            $scope.manageOnlySingleStichingColor(color, price, colorTitle, colorId, grpName, styleCode);
        } else if (styleCode && styleCode == "TOP") {
            if (layerName == "LAYER4") {
                $scope.manageOnlySingleStichingColor(color, price, colorTitle, colorId, grpName, styleCode);
            }
        } else if (styleCode && styleCode == "SIDE") {
            if (layerName == "Layer5") {
                $scope.manageOnlySingleStichingColor(color, price, colorTitle, colorId, grpName, styleCode);
            }
        }
        angular.element(".part-layer-holder div.active").css({
            backgroundColor: "#" + color
        });
        var arr = hex2Rgb("0x" + color);
        angular.forEach(canvasViewArray, function (canobj, i) {
            canvas = canobj.canvas;
            var objects = canvas.getObjects();
            for (var j = 0; j < objects.length; j++) {
                if (objects[j].get("partName") == parentName) {
                    var groupobjects = objects[j].getObjects();
                    for (var k = 0; k < groupobjects.length; k++) {
                        if (groupobjects[k].get("partName") == layerName) {
                            global_SelectedItem = groupobjects[k];
                            colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                            global_SelectedItem.set({
                                hexColorCode: color
                            });
                            global_SelectedItem.fill = colorPickerValue;
                            colorPickerValue = colorPickerValue;
                            global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                            global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                            break;
                        }
                    }
                }
            }
        });
        if (mobileMode) {
            $(".tab-content").css({
                display: "none"
            });
        }
        $scope.calculateTotalPrice();
    };
    $scope.manageOnlySingleStichingColor = function (color, price, colorTitle, colorId, uniGrpName, styleCode) {
        angular.forEach(partDropDownArr, function (groupLayers) {
            angular.forEach(groupLayers.layers, function (layerobj) {
                var groupCode = layerobj.styleCode;
                var layerName = layerobj.layerName;
                if (groupCode == "BSE" && (layerName == "LAYER8" || layerName == "LAYER12" || layerName == "LAYER14")) {
                    layerobj.hexColorCode = color;
                    layerobj.layerColorPrice = price;
                    layerobj.colorTitle = colorTitle;
                    layerobj.colorId = colorId;
                } else if ((groupCode == "SHPAD" || groupCode == "DBSP") && (layerName == "LAYER6" || layerName == "LAYER8")) {
                    layerobj.hexColorCode = color;
                    layerobj.layerColorPrice = price;
                    layerobj.colorTitle = colorTitle;
                    layerobj.colorId = colorId;
                } else if (groupCode && groupCode == "TOP") {
                    if (layerName == "LAYER4") {
                        layerobj.hexColorCode = color;
                        layerobj.layerColorPrice = price;
                        layerobj.colorTitle = colorTitle;
                        layerobj.colorId = colorId;
                    }
                } else if (groupCode && groupCode == "SIDE") {
                    if (layerName == "Layer5") {
                        layerobj.hexColorCode = color;
                        layerobj.layerColorPrice = price;
                        layerobj.colorTitle = colorTitle;
                        layerobj.colorId = colorId;
                    }
                }
            });
        });
        angular.forEach(selectedPartInfoObj, function (partInfo, key) {
            var layerName = partInfo.layerName;
            if (uniGrpName == "Body" && (layerName == "LAYER8" || layerName == "LAYER12" || layerName == "LAYER14")) {
                selectedPartInfoObj[key].hexColorCode = color;
                selectedPartInfoObj[key].layerColorPrice = price;
                selectedPartInfoObj[key].colorTitle = colorTitle;
                selectedPartInfoObj[key].colorId = colorId;
            } else if ((uniGrpName == "ShoulderPad" || uniGrpName == "Double Shoulder Pad") && (layerName == "LAYER6" || layerName == "LAYER8")) {
                selectedPartInfoObj[key].hexColorCode = color;
                selectedPartInfoObj[key].layerColorPrice = price;
                selectedPartInfoObj[key].colorTitle = colorTitle;
                selectedPartInfoObj[key].colorId = colorId;
            } else if (styleCode && styleCode == "TOP") {
                if (layerName == "LAYER4") {
                    selectedPartInfoObj[key].hexColorCode = color;
                    selectedPartInfoObj[key].layerColorPrice = price;
                    selectedPartInfoObj[key].colorTitle = colorTitle;
                    selectedPartInfoObj[key].colorId = colorId;
                }
            } else if (styleCode && styleCode == "SIDE") {
                if (layerName == "Layer5") {
                    selectedPartInfoObj[key].hexColorCode = color;
                    selectedPartInfoObj[key].layerColorPrice = price;
                    selectedPartInfoObj[key].colorTitle = colorTitle;
                    selectedPartInfoObj[key].colorId = colorId;
                }
            }
        });
        $scope.selectedPartInfoObj = selectedPartInfoObj;
        singleStichingColor = color;
        singleStichingColorID = colorId;
        singleStichingColorTitle = colorTitle;
        var arr = hex2Rgb("0x" + color);
        angular.forEach(canvasViewArray, function (canobj, i) {
            canvas = canobj.canvas;
            var objects = canvas.getObjects();
            for (var j = 0; j < objects.length; j++) {
                if (objects[j].get("shapeType") == "grp") {
                    var grpName = objects[j].get("groupTitle");
                    var groupobjects = objects[j].getObjects();
                    for (var k = 0; k < groupobjects.length; k++) {
                        var layerName = groupobjects[k].get("partName");
                        var partStyleCode = groupobjects[k].get("partStyleCode");
                        if (grpName) {
                            if (grpName == "Body" && (layerName == "LAYER8" || layerName == "LAYER12" || layerName == "LAYER14" || layerName == "LAYER8")) {
                                global_SelectedItem = groupobjects[k];
                                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                                global_SelectedItem.set({
                                    hexColorCode: color
                                });
                                global_SelectedItem.fill = colorPickerValue;
                                colorPickerValue = colorPickerValue;
                                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                            } else if ((grpName == "ShoulderPad" || grpName == "Double Shoulder Pad") && (layerName == "LAYER6" || layerName == "LAYER8")) {
                                global_SelectedItem = groupobjects[k];
                                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                                global_SelectedItem.set({
                                    hexColorCode: color
                                });
                                global_SelectedItem.fill = colorPickerValue;
                                colorPickerValue = colorPickerValue;
                                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                            } else if (partStyleCode == "TOP" && layerName == "LAYER4") {
                                global_SelectedItem = groupobjects[k];
                                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                                global_SelectedItem.set({
                                    hexColorCode: color
                                });
                                global_SelectedItem.fill = colorPickerValue;
                                colorPickerValue = colorPickerValue;
                                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                            } else if (partStyleCode == "SIDE" && layerName == "Layer5") {
                                global_SelectedItem = groupobjects[k];
                                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                                global_SelectedItem.set({
                                    hexColorCode: color
                                });
                                global_SelectedItem.fill = colorPickerValue;
                                colorPickerValue = colorPickerValue;
                                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                            }
                        }
                    }
                }
            }
        });
        $scope.calculateTotalPrice();
    };
    $scope.make3DRotation = function () {
        var arr = angular.element(".canvas-case-inner div.canvas-container");
        angular.element(arr[0]).css({
            display: "block"
        });
    };
    $scope.showRotation = function () {
        if (strapSelectedIndx != 10) {
            strapSelectedIndx == 0 ? angular.element(".single-check").addClass("c_on") : angular.element(".double-check").addClass("c_on");
        }
        $scope.make3DRotation();
        effectCounter = 0;
        var setInter = setInterval(function () {
            effectCounter += 1;
            if (effectCounter < canvasViewArray.length) {
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[effectCounter]).css({
                    display: "block"
                });
            } else {
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[0]).css({
                    display: "block"
                });
                clearInterval(setInter);
            }
        }, 200);
    };
    $scope.rotateLeftWise = function () {
        if (nextCounter == 0) {
            nextCounter = canvasViewArray.length;
        }
        nextCounter -= 1;
        if (nextCounter == canvasViewArray.length / 2) {
            currentView = canvasViewArray.length / 2;
        } else if (nextCounter == 0) {
            currentView = 0;
        }
        angular.element(".canvas-case-inner div.canvas-container").css({
            display: "none"
        });
        var arr = angular.element(".canvas-case-inner div.canvas-container");
        angular.element(arr[nextCounter]).css({
            display: "block"
        });
    };
    $scope.rotateRightWise = function () {
        if (nextCounter == canvasViewArray.length - 1) {
            nextCounter = -1;
        }
        nextCounter += 1;
        if (nextCounter == canvasViewArray.length / 2) {
            currentView = canvasViewArray.length / 2;
        } else if (nextCounter == 0) {
            currentView = 0;
        }
        angular.element(".canvas-case-inner div.canvas-container").css({
            display: "none"
        });
        var arr = angular.element(".canvas-case-inner div.canvas-container");
        angular.element(arr[nextCounter]).css({
            display: "block"
        });
    };
    $scope.undoObjects = function () {
        undo();
    };
    $scope.loadPocketLayers = function () {
        if (viewCounter < canvasViewArray.length) {
            if (pocketCounter < pocketData.pockets[viewCounter].images.length) {
                fabric.Image.fromURL(sitePath + pocketData.pockets[viewCounter].images[pocketCounter].src, function (img) {
                    var cW = 140;
                    var cH = 140;
                    var oW = 421;
                    var oH = 223;
                    var cR = cW / cH;
                    var oR = oW / oH;
                    var nW = 20;
                    var nH = 20;
                    if (cR > oR) {
                        nH = cH;
                        nW = oW * nH / oH;
                    } else {
                        nW = cW;
                        nH = nW * oH / oW;
                    }
                    var width = ratioWidth * 421 / originalWidth;
                    var height = ratioHeight * 223 / originalHeight;
                    var left = (canWidth - width) / 2;
                    var top = (canHeight - height) / 2;
                    img1[0][pocketCounter] = img.set({
                        perPixelTargetFind: true,
                        selectable: false,
                        partName: pocketData.pockets[viewCounter].images[pocketCounter].layerName,
                        width: width,
                        height: height
                    });
                    canvas = canvasViewArray[viewCounter].canvas;
                    var color = "000000";
                    var arr = hex2Rgb("0x" + color);
                    global_SelectedItem = img;
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                    pocketCounter++;
                    if (pocketCounter < pocketData.pockets[viewCounter].images.length) {
                        $scope.loadPocketLayers();
                    } else {
                        canvas = canvasViewArray[viewCounter].canvas;
                        var grpObj = new fabric.Group(img1[0], {
                            perPixelTargetFind: true,
                            shapeType: "pocketGrp",
                            partName: pocketTitleName,
                            left: left,
                            top: top,
                            hasControls: false,
                            hasBorders: false
                        });
                        canvas.add(grpObj);
                        canvas.renderAll();
                        viewCounter++;
                        pocketCounter = 0;
                        img1[0] = new Array;
                        $scope.loadPocketLayers();
                    }
                });
            }
        }
    };
    $scope.setLayerByIndx = function (indx, layerId) {
        if (selectedPartInfoObj) {
            var partLayerName = selectedPartInfoObj[0].groupName;
            var partLayerLayerName = selectedPartInfoObj[indx].layerName;
            if (partLayerName == "Body" || partLayerName == "Pocket") {
                if (partLayerLayerName == "LAYER13" || partLayerLayerName == "LAYER14") {
                    if (nextCounter != canvasViewArray.length / 2) {
                        nextCounter = canvasViewArray.length / 2;
                        currentView = canvasViewArray.length / 2;
                        var can = canvasViewArray[nextCounter].canvas;
                        can.discardActiveObject();
                        can.renderAll();
                        angular.element(".canvas-case-inner div.canvas-container").css({
                            display: "none"
                        });
                        var arr = angular.element(".canvas-case-inner div.canvas-container");
                        angular.element(arr[nextCounter]).css({
                            display: "block"
                        });
                    }
                } else if (nextCounter != 0) {
                    nextCounter = 0;
                    currentView = 0;
                    var can = canvasViewArray[nextCounter].canvas;
                    can.discardActiveObject();
                    can.renderAll();
                    angular.element(".canvas-case-inner div.canvas-container").css({
                        display: "none"
                    });
                    var arr = angular.element(".canvas-case-inner div.canvas-container");
                    angular.element(arr[nextCounter]).css({
                        display: "block"
                    });
                }
            } else if (partLayerName == "ShoulderPad" || partLayerName == "Double Shoulder Pad") {
                if (nextCounter != canvasViewArray.length / 2) {
                    nextCounter = canvasViewArray.length / 2;
                    currentView = canvasViewArray.length / 2;
                    var can = canvasViewArray[nextCounter].canvas;
                    can.discardActiveObject();
                    can.renderAll();
                    angular.element(".canvas-case-inner div.canvas-container").css({
                        display: "none"
                    });
                    var arr = angular.element(".canvas-case-inner div.canvas-container");
                    angular.element(arr[nextCounter]).css({
                        display: "block"
                    });
                }
            }
        }
        $scope.openPreloader("Loading Design Colors");
        angular.element(".part-layer-holder div").removeClass("active");
        var childs = angular.element(".part-layer-holder div");
        angular.element(childs[indx]).addClass("active");
        $scope.managePartsAndLayers(layerId, "dontHide");
    };
    $scope.selectPartByName = function (partName, layers, indx, groupName) {
        $scope.openPreloader("Loading Design Colors");
        selectedPartIndx = indx;
        selectedPartInfoObj = layers;
        angular.element(".selected-part-title").text(partName);
        $scope.selectedPartInfoObj = selectedPartInfoObj;
        var can = canvasViewArray[nextCounter].canvas;
        can.discardActiveObject();
        can.renderAll();
        if (groupName == "Pocket" || groupName == "Handle") {
            if (nextCounter != 0) {
                nextCounter = 0;
                currentView = 0;
                var can = canvasViewArray[nextCounter].canvas;
                can.discardActiveObject();
                can.renderAll();
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        } else if (groupName == "ShoulderPad" || groupName == "Double Shoulder Pad") {
            if (nextCounter != canvasViewArray.length / 2) {
                nextCounter = canvasViewArray.length / 2;
                currentView = canvasViewArray.length / 2;
                var can = canvasViewArray[nextCounter].canvas;
                can.discardActiveObject();
                can.renderAll();
                angular.element(".canvas-case-inner div.canvas-container").css({
                    display: "none"
                });
                var arr = angular.element(".canvas-case-inner div.canvas-container");
                angular.element(arr[nextCounter]).css({
                    display: "block"
                });
            }
        }
        if (groupName == "Pocket" || groupName == "Handle" || groupName == "ShoulderPad" || groupName == "Double Shoulder Pad") {
            $scope.removeSelectedFlag = true;
        } else {
            $scope.removeSelectedFlag = false;
        }
        $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId);
    };
    $scope.hidePopUp = function (str) {
        if (str == "video") {
            angular.element(".video-wrapper").css({
                display: "none"
            });
            var bk_src = $("#videoT").attr("src");
            $("#videoT").attr("src", "").promise().done(function () {
                $("#videoT").attr("src", bk_src);
            });
            return false;
        } else if (str == "preloader") {
            angular.element(".preloader, .lightbox").fadeOut(300);
        } else if (str == "duplicate") {
            angular.element(".lightbox, #duplicate-check-php").fadeOut(300);
        } else if (str == "sizecheck") {
            angular.element(".lightbox, #size-check-php").fadeOut(300);
        } else if (str == "save-check") {
            angular.element(".lightbox2").css({
                display: "none"
            });
            angular.element("#save-check-php").css({
                display: "none"
            });
            detectArr = new Array;
        } else if (str == "printing-check") {
            angular.element(".lightbox").css({
                display: "none"
            });
            angular.element("#printing-check-php").css({
                display: "none"
            });
            detectArr = new Array;
        } else if (str == "savepopup") {
            angular.element(".save-wrapper").fadeOut(300);
        } else if (str == "preview") {
            angular.element(".lightbox, .preview-case-box").fadeOut(300);
        } else if (str == "login") {
            angular.element(".lightbox, #save_pop_up").fadeOut(300);
        } else {
            angular.element(".lightbox, .perfect-case-box").fadeOut(300);
        }
    };
    $scope.loginRegister = function (str) {
        if (loginType != "admin") {
            angular.element("#save_pop_up label.label_check").removeClass("c_on");
            angular.element("#save_pop_up input").val("");
            angular.element("#save_pop_up div.form-group").removeClass("has-error");
            if (str == "login") {
                angular.element(".login-check").addClass("c_on");
                angular.element(".login-container").css({
                    display: "block"
                });
                angular.element(".register-container").css({
                    display: "none"
                });
            } else {
                angular.element(".register-check").addClass("c_on");
                angular.element(".login-container").css({
                    display: "none"
                });
                angular.element(".register-container").css({
                    display: "block"
                });
            }
            $scope.openPopUp("login");
        }
    };
    $scope.manageLoginRegister = function (str) {
        if (loginType != "admin") {
            angular.element("#save_pop_up label.label_check").removeClass("c_on");
            angular.element("#save_pop_up input").val("");
            angular.element("#save_pop_up div.form-group").removeClass("has-error");
            if (str == "login") {
                angular.element(".login-check").addClass("c_on");
                angular.element(".login-container").css({
                    display: "block"
                });
                angular.element(".register-container").css({
                    display: "none"
                });
            } else {
                angular.element(".register-check").addClass("c_on");
                angular.element(".login-container").css({
                    display: "none"
                });
                angular.element(".register-container").css({
                    display: "block"
                });
            }
        }
    };
    $scope.openPopUp = function (str) {
        if (str == "video") {
            angular.element(".video-wrapper").css({
                display: "block"
            });
        } else if (str == "save") {
            angular.element(".save-wrapper").fadeIn(300);
        } else if (str == "duplicate") {
            angular.element(".lightbox, #duplicate-check-php").fadeIn(300);
        } else if (str == "sizecheck") {
            angular.element(".lightbox, #size-check-php").fadeIn(300);
        } else if (str == "login") {
            angular.element(".lightbox, #save_pop_up").fadeIn(300);
        } else {
            angular.element(".lightbox, .perfect-case-box").fadeIn(300);
        }
    };
    $scope.openPreloader = function (str) {
        if (str) {
            angular.element(".loading-heading").text(str + "...");
        } else {
            angular.element(".loading-heading").text("Loading Design...");
        }
        angular.element(".preloader, .lightbox").fadeIn(300);
    };
    $scope.selectPrinting = function () {
        $scope.hidePopUp("printing-check");
    };
    $scope.previewPopUpOpen = function () {
        angular.element(".textFont-check").css({
            display: "none"
        });
        $scope.openPreloader();
        previewRatioX = previewWidth / canWidth;
        previewRatioY = previeHeight / canHeight;
        previewRatioPX = previewWidth / canWidth;
        previewRatioPY = previeHeight / canHeight;
        if (window.innerWidth < 767) {
            var left = (angular.element(".previewBoxHolder").width() - previewWidth) / 2 - 39;
        } else {
            var left = (angular.element(".previewBoxHolder").width() - previewWidth) / 2;
        }
        var top = (angular.element(".previewBoxHolder").height() - previeHeight) / 2;
        angular.element(".previewCanvas").css({
            left: left + "px",
            top: top + "px",
            width: previewWidth + "px",
            position: "absolute"
        });
        previewCanvas.clear();
        previewCanvas.renderAll();
        var preCan = canvasViewArray[nextCounter].canvas;
        previewCounter = 0;
        selectedCanvasObjects = preCan.getObjects();
        $scope.managePreviewSection();
    };
    $scope.managePreviewSection = function () {
        if (previewCounter < selectedCanvasObjects.length) {
            if (selectedCanvasObjects[previewCounter].get("shapeType") == "grp") {
                layerCounter = 0;
                $scope.loadPreviewGroupLayers();
            } else {
                $scope.loadPreviewNormalLayersInDesign();
            }
        } else {
            var cloneC = 0;
            previewCanvas.forEachObject(function (obj) {
                if (previewCanvas.item(cloneC).get("name") == "cloneStrap") {
                    previewCanvas.bringToFront(previewCanvas.item(cloneC));
                    previewCanvas.renderAll();
                }
                cloneC++;
            });
            $scope.hidePopUp("preloader");
            angular.element(".lightbox, .preview-case-box").fadeIn(300);
        }
    };
    $scope.loadArtInDesignCanvas = function () {
        setTimeout(function () {
            $scope.addArtAndTextOnSelectedCanvas();
        }, 500);
    };
    $scope.addArtAndTextOnSelectedCanvas = function () {
        if (designDataArr) {
            designDataArrayCounter = 0;
            designDataLayerCounter = 0;
            $scope.manageDesignDataArrayInEdit();
        } else {
            $scope.optionsSizesArr = optionsSizesArr;
            onlyOneTimeRotFlag = false;
            $scope.hidePopUp("preloader");
            otherPartLoadTitle = "";
            selectedPartIndx = 0;
            $scope.partDropDownArr = partDropDownArr;
            selectedPartInfoObj = partDropDownArr[selectedPartIndx].layers;
            $scope.selectedPartInfoObj = selectedPartInfoObj;
            $scope.showRotation();
            $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId);
            $scope.calculateTotalPrice();
        }
    };
    $scope.manageDesignDataArrayInEdit = function () {
        if (designDataArrayCounter < designDataArr.length) {
            var canCtr = designDataArrayCounter == 0 ? 0 : canvasViewArray.length / 2;
            canvas = canvasViewArray[canCtr].canvas;
            if (designDataLayerCounter < designDataArr[designDataArrayCounter].length) {
                if (designDataArr[designDataArrayCounter][designDataLayerCounter].blankData == "yes") {
                    designDataArrayCounter++;
                    designDataLayerCounter = 0;
                    $scope.manageDesignDataArrayInEdit();
                } else {
                    $scope.loadArtAndTextFromDesignArray();
                }
            } else {
                designDataArrayCounter++;
                designDataLayerCounter = 0;
                $scope.manageDesignDataArrayInEdit();
            }
        } else {
            $scope.optionsSizesArr = optionsSizesArr;
            onlyOneTimeRotFlag = false;
            $scope.hidePopUp("preloader");
            otherPartLoadTitle = "";
            selectedPartIndx = 0;
            $scope.partDropDownArr = partDropDownArr;
            selectedPartInfoObj = partDropDownArr[selectedPartIndx].layers;
            $scope.selectedPartInfoObj = selectedPartInfoObj;
            $scope.showRotation();
            $scope.managePartsAndLayers(selectedPartInfoObj[0].layerId);
            $scope.calculateTotalPrice();
        }
    };
    $scope.manageTextAndArtSection = function () {
        if (previewCounter < selectedCanvasObjects.length) {
            $scope.loadPreviewNormalLayers();
        } else {
            $scope.hidePopUp("preloader");
            angular.element(".lightbox, .preview-case-box").fadeIn(300);
        }
    };
    $scope.loadPreviewGroupLayers = function () {
        var objects = selectedCanvasObjects[previewCounter].getObjects();
        if (layerCounter < objects.length) {
            var imageSrc = layersPath + objects[layerCounter].get("partStyleCode") + "/" + objects[layerCounter].get("layerCode") + previewImagePath + objects[layerCounter].get("angleName") + ".png";
            fabric.Image.fromURL(imageSrc, function (img) {
                var width = img.width;
                var height = img.height;
                if (selectedCanvasObjects[previewCounter].get("groupTitle") == "Pocket") {
                    var left = selectedCanvasObjects[previewCounter].getLeft() * previewRatioPX;
                    var top = selectedCanvasObjects[previewCounter].getTop() * previewRatioPY;
                    img.set({
                        left: left,
                        top: top,
                        selectable: false
                    });
                } else {
                    var left = (previewWidth - width) / 2;
                    var top = (previeHeight - height) / 2;
                    img.set({
                        left: left,
                        top: top,
                        selectable: false
                    });
                }
                var color = objects[layerCounter].get("hexColorCode");
                if (color) {
                    var arr = hex2Rgb("0x" + color);
                    global_SelectedItem = img;
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                }
                previewCanvas.add(img);
                previewCanvas.renderAll();
                if (selectedCanvasObjects[previewCounter].get("groupTitle") == "Double Shoulder Pad" && nextCounter == canvasViewArray.length / 2) {
                    img.set({
                        scaleX: 0.8,
                        left: img.getLeft() - 5
                    });
                    previewCanvas.renderAll();
                    var cloneObj = fabric.util.object.clone(img);
                    if (color) {
                        var arr = hex2Rgb("0x" + color);
                        global_SelectedItem = cloneObj;
                        colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                        global_SelectedItem.set({
                            hexColorCode: color
                        });
                        global_SelectedItem.fill = colorPickerValue;
                        colorPickerValue = colorPickerValue;
                        global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                        global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                    }
                    cloneObj.set({
                        left: img.getLeft() + 45,
                        angle: 3,
                        name: "cloneStrap"
                    });
                    previewCanvas.add(cloneObj);
                    previewCanvas.renderAll();
                }
                layerCounter++;
                if (layerCounter < objects.length) {
                    $scope.loadPreviewGroupLayers();
                } else {
                    previewCounter++;
                    $scope.managePreviewSection();
                }
            });
        }
    };
    $scope.loadArtAndTextFromDesignArray = function () {
        var object = designDataArr[designDataArrayCounter][designDataLayerCounter];
        if (object.shapeType == "Clipart" || object.shapeType == "UploadImage") {
            var previousHeightSizePrice = object.previousHeightSizePrice ? object.previousHeightSizePrice : 0;
            var previousWidthSizePrice = object.previousWidthSizePrice ? object.previousHeightSizePrice : 0;
            var print = object.print ? object.print : 0;
            var elementIdentifire = object.elementIdentifire ? object.elementIdentifire : "";
            var httpUrl = object.shapeType == "Clipart" ? clipArtPath + object.thumbSrc : uploadLarge + object.thumbSrc;
            httpUrl = httpUrl.replace("/224X224/", "/original/");
            fabric.Image.fromURL(httpUrl, function (img) {
                img.set({
                    width: parseFloat(object.width),
                    height: parseFloat(object.height),
                    left: parseFloat(object.x),
                    top: parseFloat(object.y),
                    elementIdentifire: elementIdentifire,
                    previousHeightSizePrice: previousHeightSizePrice,
                    previousWidthSizePrice: previousWidthSizePrice,
                    name: object.shapeType,
                    shapeType: object.shapeType,
                    selectable: true,
                    print: print,
                    optionId: object.optionId,
                    colorId: object.colorId,
                    price: parseFloat(object.price),
                    objTitle: object.objTitle,
                    colorPrice: object.colorPrice,
                    artColorSelectedIndx: object.artColorSelectedIndx,
                    colorable: object.colorable,
                    thumbSrc: object.thumbSrc,
                    colorTitle: object.colorTitle,
                    angle: parseFloat(object.angle)
                });
                if (object.colorable == 1) {
                    var color = object.hexColorCode;
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color,
                        colorId: object.colorId
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                }
                canvas.add(img);
                canvas.renderAll();
                designDataLayerCounter++;
                $scope.manageDesignDataArrayInEdit();
            });
        }
        if (object.shapeType == "Option") {
            optionsSizesArr = new Array;
            var obj = new Object;
            obj.id = object.optionId;
            obj.label = object.objTitle;
            var layers = new Array;
            var layerObj = new Object;
            layerObj.id = object.optionId;
            layerObj.color = object.hexColorCode;
            layers.push(layerObj);
            obj.layers = layers;
            optionsSizesArr.push(obj);
            var httpUrl = sizes_path + object.thumbSrc;
            fabric.Image.fromURL(httpUrl, function (img) {
                var left = 18;
                var top = 60;
                img.set({
                    width: parseFloat(object.width),
                    height: parseFloat(object.height),
                    left: left,
                    top: top,
                    name: object.shapeType,
                    shapeType: object.shapeType,
                    selectable: true,
                    hasBorders: false,
                    lockMovementX: true,
                    lockMovementY: true,
                    price: parseFloat(object.price),
                    objTitle: object.objTitle,
                    optionId: object.optionId,
                    colorId: object.colorId,
                    colorPrice: object.colorPrice,
                    artColorSelectedIndx: object.artColorSelectedIndx,
                    colorable: object.colorable,
                    thumbSrc: object.thumbSrc,
                    colorTitle: object.colorTitle,
                    angle: parseFloat(object.angle)
                });
                img.setControlVisible("tr", false);
                img.setControlVisible("br", false);
                img.setControlVisible("bl", false);
                img.setControlVisible("tl", true);
                img.setControlVisible("mr", false);
                img.setControlVisible("mt", false);
                img.setControlVisible("mb", false);
                img.setControlVisible("ml", false);
                if (object.hexColorCode) {
                    var color = object.hexColorCode;
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
                }
                canvas.add(img);
                selectedSizeObj = img;
                canvas.renderAll();
                designDataLayerCounter++;
                $scope.manageDesignDataArrayInEdit();
            });
        }
        if (object.shapeType == "Text") {
            var scaleX = 1;
            var scaleY = 1;
            var newFontSize = 15;
            if (object.scaleX || object.scaleY) {
                newFontSize = fabricFontSize;
                scaleX = object.scaleX;
                scaleY = object.scaleY;
            } else {
                newFontSize = parseFloat(fabricFontSize * parseFloat(object.width) / parseFloat(object.originalTextFontWidth));
            }
            var elementIdentifire = object.elementIdentifire ? object.elementIdentifire : "";
            var printingType = object.printingType ? object.printingType : printings[0].title;
            var printingCost = object.printingCost ? object.printingCost : printings[0].cost;
            var minPrice = object.minPrice ? object.minPrice : printings[0].minPrice;
            var maxTextCharacter = object.maxTextCharacter ? object.maxTextCharacter : printings[0].maxCharacter;
            printingCost = parseFloat(printingCost);
            minPrice = parseFloat(minPrice);
            var textDec = object.textDecoration ? object.textDecoration : "none";
            var img = new fabric.Text(object.text, {
                name: "Text",
                fontFamily: object.fontFamily,
                shapeType: "Text",
                text: object.text,
                fontSize: newFontSize,
                selectable: true,
                scaleX: scaleX,
                scaleY: scaleY,
                fill: "#" + object.hexColorCode,
                elementIdentifire: elementIdentifire,
                textAlign: object.textAlign,
                colorId: object.colorId,
                textDecoration: textDec,
                colorTitle: object.colorTitle,
                boldClick: object.boldClick,
                italicClick: object.italicClick,
                originalTextFontWidth: object.originalTextFontWidth,
                originalTextFontHeight: object.originalTextFontHeight,
                artColorSelectedIndx: object.textColorSelectedIndx,
                selectedTextFont: object.selectedTextFont,
                hexColorCode: object.hexColorCode,
                fontTitle: object.fontTitle,
                colorPrice: object.colorPrice,
                angle: parseFloat(object.angle),
                printingType: printingType,
                printingCost: printingCost,
                minPrice: minPrice,
                maxTextCharacter: maxTextCharacter
            });
            canvas.add(img);
            canvas.renderAll();
            img.set({
                left: parseFloat(object.x),
                top: parseFloat(object.y),
                angle: object.angle
            });
            img.setCoords();
            canvas.calcOffset();
            canvas.renderAll();
            designDataLayerCounter++;
            $scope.manageDesignDataArrayInEdit();
        }
    };
    $scope.loadPreviewNormalLayersInEdit = function () {
        var object = dataArray[previewCounter];
        if (object.shapeType == "Clipart" || object.shapeType == "UploadImage") {
            var httpUrl = object.shapeType == "Clipart" ? clipArtPath + object.thumbSrc.replace("/100X100/", "/original/") : uploadLarge + object.thumbSrc;
            fabric.Image.fromURL(httpUrl, function (img) {
                img.set({
                    width: parseFloat(object.width),
                    height: parseFloat(object.height),
                    left: parseFloat(object.x),
                    top: parseFloat(object.y),
                    name: object.shapeType,
                    shapeType: object.shapeType,
                    selectable: true,
                    price: parseFloat(object.price),
                    objTitle: object.objTitle,
                    colorPrice: object.colorPrice,
                    artColorSelectedIndx: object.artColorSelectedIndx,
                    colorable: object.colorable,
                    thumbSrc: object.thumb,
                    colorTitle: object.colorTitle,
                    angle: parseFloat(object.angle)
                });
                if (object.colorable == 1) {
                    var color = object.hexColorCode;
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(designCan.renderAll.bind(designCan));
                }
                designCan.add(img);
                designCan.renderAll();
                previewCounter++;
                $scope.loadArtInDesignCanvas();
            });
        }
        if (object.shapeType == "Text") {
            var newFontSize = parseFloat(fabricFontSize * parseFloat(object.width) / parseFloat(object.originalTextFontWidth));
            var img = new fabric.Text(object.text, {
                name: "Text",
                fontFamily: object.fontFamily,
                shapeType: "Text",
                text: object.text,
                fontSize: newFontSize,
                selectable: true,
                fill: "#" + object.hexColorCode,
                textAlign: object.textAlign,
                colorTitle: object.colorTitle,
                boldClick: object.boldClick,
                italicClick: object.italicClick,
                artColorSelectedIndx: object.textColorSelectedIndx,
                selectedTextFont: object.selectedTextFont,
                hexColorCode: object.hexColorCode,
                fontTitle: object.fontTitle,
                colorPrice: object.colorPrice,
                angle: parseFloat(object.angle)
            });
            designCan.add(img);
            designCan.renderAll();
            img.set({
                left: parseFloat(object.x),
                top: parseFloat(object.y),
                angle: object.angle
            });
            img.setCoords();
            designCan.calcOffset();
            designCan.renderAll();
            previewCounter++;
            $scope.loadArtInDesignCanvas();
        }
    };
    $scope.loadPreviewNormalLayers = function () {
        var object = selectedCanvasObjects[previewCounter];
        if (object.get("shapeType") == "Clipart" || object.get("shapeType") == "UploadImage") {
            var httpUrl = object.get("shapeType") == "Clipart" ? clipArtPath + object.get("thumbSrc").replace("/100X100/", "/original/") : uploadLarge + object.get("thumbSrc");
            fabric.Image.fromURL(httpUrl, function (img) {
                var rotationObj = object.get("oCoords").tl;
                img.set({
                    width: object.getWidth() * previewRatioX,
                    height: object.getHeight() * previewRatioY,
                    left: angular.element(".design-case-inner").position().left + rotationObj.x * previewRatioX,
                    top: angular.element(".design-case-inner").position().top + rotationObj.y * previewRatioY,
                    selectable: false
                });
                if (object.get("colorable") == 1) {
                    var color = object.get("hexColorCode");
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                }
                previewCanvas.add(img);
                previewCanvas.renderAll();
                previewCounter++;
                $scope.manageTextAndArtSection();
            });
        }
        if (object.get("shapeType") == "Text") {
            var newFontSize = fabricFontSize * object.getWidth() / object.originalTextFontWidth;
            newFontSize = newFontSize * previewRatioX;
            var rotationObj = object.get("oCoords").tl;
            var img = new fabric.Text(object.text, {
                name: "Text",
                fontFamily: object.fontFamily,
                text: object.text,
                fontSize: newFontSize,
                selectable: false,
                fill: "#" + object.hexColorCode,
                textAlign: object.textAlign
            });
            previewCanvas.add(img);
            previewCanvas.renderAll();
            img.set({
                left: angular.element(".design-case-inner").position().left + rotationObj.x * previewRatioX,
                top: angular.element(".design-case-inner").position().top + rotationObj.y * previewRatioY,
                angle: object.angle
            });
            img.setCoords();
            previewCanvas.calcOffset();
            previewCanvas.renderAll();
            previewCounter++;
            $scope.manageTextAndArtSection();
        }
    };
    $scope.loadPreviewNormalLayers = function () {
        var object = selectedCanvasObjects[previewCounter];
        if (object.get("shapeType") == "Clipart" || object.get("shapeType") == "UploadImage") {
            var httpUrl = object.get("shapeType") == "Clipart" ? clipArtPath + object.get("thumbSrc").replace("/100X100/", "/original/") : uploadLarge + object.get("thumbSrc");
            fabric.Image.fromURL(httpUrl, function (img) {
                var rotationObj = object.get("oCoords").tl;
                img.set({
                    width: object.getWidth() * previewRatioX,
                    height: object.getHeight() * previewRatioY,
                    left: angular.element(".design-case-inner").position().left + rotationObj.x * previewRatioX,
                    top: angular.element(".design-case-inner").position().top + rotationObj.y * previewRatioY,
                    selectable: false
                });
                if (object.get("colorable") == 1) {
                    var color = object.get("hexColorCode");
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                }
                previewCanvas.add(img);
                previewCanvas.renderAll();
                previewCounter++;
                $scope.manageTextAndArtSection();
            });
        }
        if (object.get("shapeType") == "Text") {
            var newFontSize = fabricFontSize * object.getWidth() / object.originalTextFontWidth;
            newFontSize = newFontSize * previewRatioX;
            var rotationObj = object.get("oCoords").tl;
            var img = new fabric.Text(object.text, {
                name: "Text",
                fontFamily: object.fontFamily,
                text: object.text,
                fontSize: newFontSize,
                selectable: false,
                fill: "#" + object.hexColorCode,
                textAlign: object.textAlign
            });
            previewCanvas.add(img);
            previewCanvas.renderAll();
            img.set({
                left: angular.element(".design-case-inner").position().left + rotationObj.x * previewRatioX,
                top: angular.element(".design-case-inner").position().top + rotationObj.y * previewRatioY,
                angle: object.angle
            });
            img.setCoords();
            previewCanvas.calcOffset();
            previewCanvas.renderAll();
            previewCounter++;
            $scope.manageTextAndArtSection();
        }
    };
    $scope.loadPreviewNormalLayersInDesign = function () {
        var object = selectedCanvasObjects[previewCounter];
        if (object.get("shapeType") == "Clipart" || object.get("shapeType") == "UploadImage") {
            var httpUrl = object.get("shapeType") == "Clipart" ? clipArtPath + object.get("thumbSrc").replace("/100X100/", "/original/") : uploadLarge + object.get("thumbSrc");
            fabric.Image.fromURL(httpUrl, function (img) {
                var rotationObj = object.get("oCoords").tl;
                img.set({
                    width: object.getWidth() * previewRatioX,
                    height: object.getHeight() * previewRatioY,
                    left: rotationObj.x * previewRatioX,
                    top: rotationObj.y * previewRatioY,
                    selectable: false
                });
                if (object.get("colorable") == 1) {
                    var color = object.get("hexColorCode");
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                }
                previewCanvas.add(img);
                previewCanvas.renderAll();
                previewCounter++;
                $scope.managePreviewSection();
            });
        }
        if (object.get("shapeType") == "Option") {
            var httpUrl = sizes_path + object.get("thumbSrc");
            fabric.Image.fromURL(httpUrl, function (img) {
                var rotationObj = object.get("oCoords").tl;
                img.set({
                    width: object.getWidth() * previewRatioX,
                    height: object.getHeight() * previewRatioY,
                    left: rotationObj.x * previewRatioX,
                    top: rotationObj.y * previewRatioY,
                    selectable: false
                });
                if (object.get("hexColorCode")) {
                    var color = object.get("hexColorCode");
                    global_SelectedItem = img;
                    var arr = hex2Rgb("0x" + color);
                    colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                    global_SelectedItem.set({
                        hexColorCode: color
                    });
                    global_SelectedItem.fill = colorPickerValue;
                    colorPickerValue = colorPickerValue;
                    global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                    global_SelectedItem.applyFilters(previewCanvas.renderAll.bind(previewCanvas));
                }
                previewCanvas.add(img);
                previewCanvas.renderAll();
                previewCounter++;
                $scope.managePreviewSection();
            });
        }
        if (object.get("shapeType") == "Text") {
            var newFontSize = fabricFontSize * object.getWidth() / object.originalTextFontWidth;
            newFontSize = newFontSize * previewRatioX;
            var rotationObj = object.get("oCoords").tl;
            var img = new fabric.Text(object.text, {
                name: "Text",
                fontFamily: object.fontFamily,
                text: object.text,
                fontSize: newFontSize,
                selectable: false,
                textDecoration: object.get("textDecoration"),
                fill: "#" + object.hexColorCode,
                textAlign: object.textAlign
            });
            previewCanvas.add(img);
            previewCanvas.renderAll();
            img.set({
                left: rotationObj.x * previewRatioX,
                top: rotationObj.y * previewRatioY,
                angle: object.angle
            });
            img.setCoords();
            previewCanvas.calcOffset();
            previewCanvas.renderAll();
            previewCounter++;
            $scope.managePreviewSection();
        }
        if (object.get("shapeType") == "topGroup") {
            var circle = new fabric.Circle({
                radius: 50 * previewRatioX,
                fill: "",
                stroke: "#666666",
                strokeWidth: 2,
                scaleY: 0.5 * previewRatioX,
                originX: "center",
                originY: "center"
            });
            var text = new fabric.Text("TOP OF LID", {
                fontSize: 15 * previewRatioX,
                top: -35 * previewRatioX,
                color: "#666666",
                fill: "#666666",
                originX: "center",
                originY: "center"
            });
            var group = new fabric.Group([circle, text], {
                left: 0,
                top: 105 * previewRatioX,
                shapeType: "topGroup",
                selectable: false,
                angle: 0
            });
            previewCanvas.add(group);
            previewCanvas.renderAll();
            previewCounter++;
            $scope.managePreviewSection();
        }
        if (object.get("shapeType") == "insideGroup") {
            var circle = new fabric.Circle({
                radius: 50 * previewRatioX,
                fill: "",
                stroke: "#666666",
                strokeWidth: 2,
                scaleY: 0.5 * previewRatioX,
                originX: "center",
                originY: "center"
            });
            var text = new fabric.Text("INSIDE OF LID", {
                fontSize: 15 * previewRatioX,
                top: -35 * previewRatioX,
                color: "#666666",
                fill: "#666666",
                originX: "center",
                originY: "center"
            });
            var group1 = new fabric.Group([circle, text], {
                left: 0,
                top: 180 * previewRatioX,
                shapeType: "insideGroup",
                selectable: false,
                angle: 0
            });
            previewCanvas.add(group1);
            previewCanvas.renderAll();
            previewCounter++;
            $scope.managePreviewSection();
        }
    };
    $scope.dataArrays = function () {
        dataArray = new Array;
        designDataArr = new Array;
        for (var j = 0; j < 2; j++) {
            designDataArr[j] = [];
            if (j == 0) {
                canvas = canvasViewArray[0].canvas;
            } else {
                canvas = canvasViewArray[canvasViewArray.length / 2].canvas;
            }
            var i = 0;
            canvas.forEachObject(function (obj) {
                var rotationObj = canvas.item(i).get("oCoords").tl;
                if (canvas.item(i).get("shapeType") == "Text") {
                    designDataArr[j].push({
                        name: canvas.item(i).get("name"),
                        text: canvas.item(i).get("text"),
                        x: rotationObj.x,
                        y: rotationObj.y,
                        width: canvas.item(i).getWidth(),
                        height: canvas.item(i).getHeight(),
                        shapeType: canvas.item(i).get("shapeType"),
                        originalTextFontWidth: canvas.item(i).get("originalTextFontWidth"),
                        originalTextFontHeight: canvas.item(i).get("originalTextFontHeight"),
                        fontTitle: canvas.item(i).get("fontTitle"),
                        hexColorCode: canvas.item(i).get("hexColorCode"),
                        artColorSelectedIndx: canvas.item(i).get("artColorSelectedIndx"),
                        fontFamily: canvas.item(i).get("fontFamily"),
                        scaleX: canvas.item(i).get("scaleX"),
                        scaleY: canvas.item(i).get("scaleY"),
                        textDecoration: canvas.item(i).get("textDecoration"),
                        textAlign: canvas.item(i).get("textAlign"),
                        colorPrice: canvas.item(i).get("colorPrice"),
                        shapeType: canvas.item(i).get("shapeType"),
                        printingType: canvas.item(i).get("printingType"),
                        maxTextCharacter: canvas.item(i).get("maxTextCharacter"),
                        printingCost: canvas.item(i).get("printingCost"),
                        minPrice: canvas.item(i).get("minPrice"),
                        elementIdentifire: canvas.item(i).get("elementIdentifire"),
                        colorTitle: canvas.item(i).get("colorTitle"),
                        colorId: canvas.item(i).get("colorId"),
                        angle: canvas.item(i).getAngle(),
                        selectedTextFont: canvas.item(i).get("selectedTextFont"),
                        boldClick: canvas.item(i).get("boldClick"),
                        italicClick: canvas.item(i).get("italicClick")
                    });
                } else if (canvas.item(i).get("shapeType") == "Clipart" || canvas.item(i).get("shapeType") == "UploadImage" || canvas.item(i).get("shapeType") == "Option") {
                    designDataArr[j].push({
                        shapeType: canvas.item(i).get("shapeType"),
                        x: rotationObj.x,
                        y: rotationObj.y,
                        width: canvas.item(i).getWidth(),
                        height: canvas.item(i).getHeight(),
                        thumbSrc: canvas.item(i).get("thumbSrc"),
                        colorable: canvas.item(i).get("colorable"),
                        hexColorCode: canvas.item(i).get("hexColorCode"),
                        objTitle: canvas.item(i).get("objTitle"),
                        optionId: canvas.item(i).get("optionId"),
                        elementIdentifire: canvas.item(i).get("elementIdentifire"),
                        colorTitle: canvas.item(i).get("colorTitle"),
                        previousHeightSizePrice: canvas.item(i).get("previousHeightSizePrice"),
                        previousWidthSizePrice: canvas.item(i).get("previousWidthSizePrice"),
                        colorId: canvas.item(i).get("colorId"),
                        artColorSelectedIndx: canvas.item(i).get("artColorSelectedIndx"),
                        price: canvas.item(i).get("price"),
                        print: canvas.item(i).get("print"),
                        colorPrice: canvas.item(i).get("colorPrice"),
                        angle: canvas.item(i).getAngle()
                    });
                }
                i++;
            });
        }
        if (designDataArr[0].length == 0) {
            designDataArr[0].push({
                blankData: "yes"
            });
        }
        if (designDataArr[1].length == 0) {
            designDataArr[1].push({
                blankData: "yes"
            });
        }
    };
    $scope.showDoubleStraps = function () {
        doubleStrapSelectionFlag = document.getElementById("strapCheck").checked == true ? true : false;
        $scope.doubleStrapSelectionFlag = doubleStrapSelectionFlag;
        if (doubleStrapSelectionFlag) {
            alert("Click on Preview Icon to see Double Strap Shoulder Pad");
        }
    };
    $scope.checkLidBounding = function () {
        if (currentView == 0) {
            if (selectedCanvas) {
                var get_Obj = selectedCanvas.getActiveObject();
            }
            if (get_Obj) {
                if (get_Obj.get("shapeType") == "Text" || get_Obj.get("shapeType") == "Clipart" || get_Obj.get("shapeType") == "UploadImage") {
                    get_Obj.set({
                        elementIdentifire: ""
                    });
                    selectedCanvas.renderAll();
                    var toplidflag = false;
                    var insidelidflag = false;
                    var boundingRect = toplidGrp.getBoundingRect();
                    var obj1 = new Object;
                    obj1.x = boundingRect.left;
                    obj1.y = boundingRect.top + 15;
                    var obj2 = new Object;
                    obj2.x = boundingRect.left + boundingRect.width;
                    obj2.y = boundingRect.top + boundingRect.height + 5;
                    var cloneObj = fabric.util.object.clone(get_Obj);
                    var objectBoundingRect = cloneObj.getBoundingRect();
                    if (objectBoundingRect.left >= obj1.x && objectBoundingRect.left + objectBoundingRect.width <= obj2.x) {
                        if (objectBoundingRect.top >= obj1.y && objectBoundingRect.top + objectBoundingRect.height <= obj2.y) {
                            toplidflag = true;
                            selectedCanvas.forEachObject(function (obj) {
                                if (obj.get("elementIdentifire") == "toplid") {
                                    selectedCanvas.remove(obj);
                                    selectedCanvas.renderAll();
                                }
                            });
                            get_Obj.set({
                                elementIdentifire: "toplid"
                            });
                            selectedCanvas.renderAll();
                        }
                    }
                    var boundingRect1 = insidelidGrp.getBoundingRect();
                    var obj3 = new Object;
                    obj3.x = boundingRect1.left;
                    obj3.y = boundingRect1.top + 15;
                    var obj4 = new Object;
                    obj4.x = boundingRect1.left + boundingRect1.width;
                    obj4.y = boundingRect1.top + boundingRect1.height + 5;
                    if (objectBoundingRect.left >= obj3.x && objectBoundingRect.left + objectBoundingRect.width <= obj4.x) {
                        if (objectBoundingRect.top >= obj3.y && objectBoundingRect.top + objectBoundingRect.height <= obj4.y) {
                            insidelidflag = true;
                            selectedCanvas.forEachObject(function (obj) {
                                if (obj.get("elementIdentifire") == "insidelid") {
                                    selectedCanvas.remove(obj);
                                    selectedCanvas.renderAll();
                                }
                            });
                            get_Obj.set({
                                elementIdentifire: "insidelid"
                            });
                            selectedCanvas.renderAll();
                        }
                    }
                }
            }
        }
    };
    $scope.calculateTotalPrice = function () {
        var allpartsWithColorArray = new Array;
        var priceInfoArr = new Array;
        var partColorsArr = new Array;
        totalPrice = 0;
        var currentPartColorObj = new Object;
        var originalColorArray = new Array;
        var frontBackTextColorPriceArr = new Array;
        angular.forEach(partDropDownArr, function (part) {
            angular.forEach(part.layers, function (layer) {
                if (layer.hexColorCode) {
                    allpartsWithColorArray.push({
                        colorId: layer.colorId,
                        layerId: layer.layerId,
                        partStyleId: part.partsStyleId
                    });
                    var layerP = layer.layerColorPrice ? parseFloat(layer.layerColorPrice) : 10;
                    originalColorArray.push({
                        color: layer.hexColorCode,
                        price: layerP,
                        partName: "color",
                        colorTitle: layer.colorTitle,
                        partId: layer.colorId,
                        colorIdentifire: "partLayers"
                    });
                }
            });
            var partTotalPrice = 0;
            partTotalPrice = parseFloat(part.selectedPartPrice);
            var obj = {
                partName: part.partName,
                partId: part.partsStyleId,
                partIdendifire: "partStyle",
                partPrice: parseFloat(partTotalPrice).toFixed(2)
            };
            priceInfoArr.push(obj);
        });
        var frontCanvas = canvasViewArray[0].canvas;
        var textColorCounter = 1;
        frontCanvas.forEachObject(function (canobj) {
            canobj.setCoords();
            frontCanvas.renderAll();
            if (canobj.get("shapeType") == "Clipart") {
                var boundingRect = frontGroupSelected.getBoundingRect();
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    withinFlag = false;
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                }
                var print = parseInt(canobj.get("print"));
                if (print == 1) {
                    withinFlag = true;
                }
                if (withinFlag == true || insideOval == true) {
                    var clipPrice = 0;
                    var wSizeRangePrice = 0;
                    var hSizeRangePrice = 0;
                    if (canobj.get("colorable") == 1) {
                        allpartsWithColorArray.push({
                            colorId: canobj.get("colorId"),
                            clipartId: canobj.get("optionId")
                        });
                        clipPrice = parseFloat(canobj.get("price"));
                        var obj3 = new Object;
                        obj3.x = boundingRect.left + 35;
                        obj3.y = boundingRect.top;
                        var obj4 = new Object;
                        obj4.x = boundingRect.left + boundingRect.width - 30;
                        obj4.y = boundingRect.top + boundingRect.height;
                        var cloneObj = fabric.util.object.clone(canobj);
                        var withinFlagSizeRange = false;
                        if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                            withinFlagSizeRange = true;
                        }
                        if (withinFlagSizeRange == true && print == 0) {
                            angular.forEach(useSizeData, function (sizedata) {
                                if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                    wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                                }
                                if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                    hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                                }
                            });
                            clipPrice += (wSizeRangePrice + hSizeRangePrice);
                            canobj.set({
                                previousWidthSizePrice: wSizeRangePrice
                            });
                            canobj.set({
                                previousHeightSizePrice: hSizeRangePrice
                            });
                        } else {
                            if (print == 0) {
                                wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                                hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                                clipPrice += (hSizeRangePrice + wSizeRangePrice);
                            }
                        }
                        originalColorArray.push({
                            color: canobj.get("hexColorCode"),
                            price: parseFloat(canobj.get("colorPrice")),
                            partName: "color",
                            colorIdentifire: "clipart",
                            colorTitle: canobj.get("colorTitle"),
                            partId: canobj.get("colorId")
                        });
                    } else {
                        allpartsWithColorArray.push({
                            clipartId: canobj.get("optionId")
                        });
                        clipPrice = parseFloat(canobj.get("price"));
                        var obj3 = new Object;
                        obj3.x = boundingRect.left + 35;
                        obj3.y = boundingRect.top;
                        var obj4 = new Object;
                        obj4.x = boundingRect.left + boundingRect.width - 30;
                        obj4.y = boundingRect.top + boundingRect.height;
                        var cloneObj = fabric.util.object.clone(canobj);
                        var withinFlagSizeRange = false;
                        if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                            withinFlagSizeRange = true;
                        }
                        if (withinFlagSizeRange == true && print == 0) {
                            angular.forEach(useSizeData, function (sizedata) {
                                if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                    wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                                }
                                if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                    hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                                }
                            });
                            clipPrice += (wSizeRangePrice + hSizeRangePrice);
                            canobj.set({
                                previousWidthSizePrice: wSizeRangePrice
                            });
                            canobj.set({
                                previousHeightSizePrice: hSizeRangePrice
                            });
                        } else {
                            if (print == 0) {
                                wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                                hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                                clipPrice += (hSizeRangePrice + wSizeRangePrice);
                            }
                        }
                    }
                    var obj = {
                        partIdendifire: "clipart",
                        partName: canobj.get("objTitle"),
                        partId: canobj.get("optionId"),
                        partPrice: clipPrice.toFixed(2)
                    };
                    priceInfoArr.push(obj);
                }
            }
            if (canobj.get("shapeType") == "Option") {
                var clipPrice = 0;
                if (canobj.get("hexColorCode")) {
                    allpartsWithColorArray.push({
                        colorId: canobj.get("colorId"),
                        optionId: canobj.get("optionId")
                    });
                    clipPrice = parseFloat(canobj.get("price"));
                    originalColorArray.push({
                        color: canobj.get("hexColorCode"),
                        price: parseFloat(canobj.get("colorPrice")),
                        colorIdentifire: "option",
                        partId: canobj.get("colorId"),
                        partName: "color",
                        colorTitle: canobj.get("colorTitle")
                    });
                } else {
                    allpartsWithColorArray.push({
                        optionId: canobj.get("optionId")
                    });
                    clipPrice = parseFloat(canobj.get("price"));
                }
                var obj = {
                    partIdendifire: "option",
                    partName: canobj.get("objTitle"),
                    partId: canobj.get("optionId"),
                    partPrice: clipPrice.toFixed(2)
                };
                priceInfoArr.push(obj);
            } else if (canobj.get("shapeType") == "UploadImage") {
                var boundingRect = frontGroupSelected.getBoundingRect();
                var print = parseInt(canobj.get("print"));
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    withinFlag = false;
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                }
                if (withinFlag == true || insideOval == true) {
                    var uploadPrice = 0;
                    var wSizeRangePrice = 0;
                    var hSizeRangePrice = 0;
                    uploadPrice = parseFloat(canobj.get("price"));
                    var obj3 = new Object;
                    obj3.x = boundingRect.left + 35;
                    obj3.y = boundingRect.top;
                    var obj4 = new Object;
                    obj4.x = boundingRect.left + boundingRect.width - 30;
                    obj4.y = boundingRect.top + boundingRect.height;
                    var cloneObj = fabric.util.object.clone(canobj);
                    var withinFlagSizeRange = false;
                    if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                        withinFlagSizeRange = true;
                    }
                    if (withinFlagSizeRange == true && print == 0) {
                        angular.forEach(useSizeData, function (sizedata) {
                            if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                            }
                            if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                            }
                        });
                        uploadPrice += (wSizeRangePrice + hSizeRangePrice);
                        canobj.set({
                            previousWidthSizePrice: wSizeRangePrice
                        });
                        canobj.set({
                            previousHeightSizePrice: hSizeRangePrice
                        });
                        frontCanvas.calcOffset();
                    } else {
                        if (print == 0) {
                            wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                            hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                            angular.element(".check-w-h").text("price" + uploadPrice + "w" + wSizeRangePrice + "h" + hSizeRangePrice + "else");
                            uploadPrice += (hSizeRangePrice + wSizeRangePrice);
                        }
                    }
                    var obj = {
                        partName: canobj.get("objTitle"),
                        partPrice: uploadPrice.toFixed(2)
                    };
                    priceInfoArr.push(obj);
                }
            } else if (canobj.get("shapeType") == "Text") {
                var boundingRect = frontGroupSelected.getBoundingRect();
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    withinFlag = false;
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                }
                if (withinFlag == true || insideOval == true) {
                    var txtString = canobj.get("text");
                    var totalLength = 0;
                    var maxTextCharacter = parseInt(canobj.get("maxTextCharacter"));
                    for (var j = 0; j < txtString.length; j++) {
                        if (txtString.charCodeAt(j) != 10 && txtString.charCodeAt(j) != 32) {
                            totalLength++;
                        }
                    }
                    if (totalLength >= 3) {
                        var textTotalPrice = parseFloat(canobj.get("minPrice")) + parseInt(totalLength - maxTextCharacter) * parseFloat(canobj.get("printingCost"));
                    } else {
                        var textTotalPrice = canobj.get("minPrice");
                    }
                    textTotalPrice = parseFloat(textTotalPrice);
                    frontBackTextColorPriceArr.push({
                        text: canobj.get("text"),
                        price: textTotalPrice
                    });
                    if (totalLength > 0) {
                        allpartsWithColorArray.push({
                            text: "text",
                            printingType: canobj.get("printingType"),
                            colorId: canobj.get("colorId")
                        });
                        var textPrice = 0;
                        textPrice = parseFloat(textTotalPrice);
                        originalColorArray.push({
                            color: canobj.get("hexColorCode") + textColorCounter,
                            printingType: canobj.get("printingType"),
                            toolLabel: txtString,
                            price: textPrice,
                            partName: "color",
                            colorIdentifire: "text",
                            colorTitle: canobj.get("colorTitle"),
                            partId: canobj.get("colorId")
                        });
                    }
                }
            }
            textColorCounter++;
        });
        var backCanvas = canvasViewArray[canvasViewArray.length / 2].canvas;
        backCanvas.forEachObject(function (canobj) {
            canobj.setCoords();
            backCanvas.renderAll();
            if (canobj.get("shapeType") == "Clipart") {
                var boundingRect = backGroupSelected.getBoundingRect();
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    withinFlag = false;
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                }
                var print = parseInt(canobj.get("print"));
                if (print == 1) {
                    withinFlag = true;
                }
                if (withinFlag == true || insideOval == true) {
                    var clipPrice = 0;
                    var wSizeRangePrice = 0;
                    var hSizeRangePrice = 0;
                    if (canobj.get("colorable") == 1) {
                        allpartsWithColorArray.push({
                            colorId: canobj.get("colorId"),
                            clipartId: canobj.get("optionId")
                        });
                        clipPrice = parseFloat(canobj.get("price"));
                        var obj3 = new Object;
                        obj3.x = boundingRect.left + 35;
                        obj3.y = boundingRect.top;
                        var obj4 = new Object;
                        obj4.x = boundingRect.left + boundingRect.width - 30;
                        obj4.y = boundingRect.top + boundingRect.height;
                        var cloneObj = fabric.util.object.clone(canobj);
                        var withinFlagSizeRange = false;
                        if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                            withinFlagSizeRange = true;
                        }
                        if (withinFlagSizeRange == true && print == 0) {
                            angular.forEach(useSizeData, function (sizedata) {
                                if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                    wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                                }
                                if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                    hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                                }
                            });
                            clipPrice += (wSizeRangePrice + hSizeRangePrice);
                            canobj.set({
                                previousWidthSizePrice: wSizeRangePrice
                            });
                            canobj.set({
                                previousHeightSizePrice: hSizeRangePrice
                            });
                        } else {
                            if (print == 0) {
                                wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                                hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                                clipPrice += (hSizeRangePrice + wSizeRangePrice);
                            }
                        }
                        originalColorArray.push({
                            color: canobj.get("hexColorCode"),
                            price: parseFloat(canobj.get("colorPrice")),
                            partName: "color",
                            colorIdentifire: "clipart",
                            colorTitle: canobj.get("colorTitle"),
                            partId: canobj.get("colorId")
                        });
                    } else {
                        allpartsWithColorArray.push({
                            clipartId: canobj.get("optionId")
                        });
                        clipPrice = parseFloat(canobj.get("price"));
                        var obj3 = new Object;
                        obj3.x = boundingRect.left + 35;
                        obj3.y = boundingRect.top;
                        var obj4 = new Object;
                        obj4.x = boundingRect.left + boundingRect.width - 30;
                        obj4.y = boundingRect.top + boundingRect.height;
                        var cloneObj = fabric.util.object.clone(canobj);
                        var withinFlagSizeRange = false;
                        if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                            withinFlagSizeRange = true;
                        }
                        if (withinFlagSizeRange == true && print == 0) {
                            angular.forEach(useSizeData, function (sizedata) {
                                if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                    wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                                }
                                if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                    hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                                }
                            });
                            clipPrice += (wSizeRangePrice + hSizeRangePrice);
                            canobj.set({
                                previousWidthSizePrice: wSizeRangePrice
                            });
                            canobj.set({
                                previousHeightSizePrice: hSizeRangePrice
                            });
                        } else {
                            if (print == 0) {
                                wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                                hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                                clipPrice += (hSizeRangePrice + wSizeRangePrice);
                            }
                        }
                    }
                    var obj = {
                        partIdendifire: "clipart",
                        partName: canobj.get("objTitle"),
                        partId: canobj.get("optionId"),
                        partPrice: clipPrice.toFixed(2)
                    };
                    priceInfoArr.push(obj);
                }
            } else if (canobj.get("shapeType") == "UploadImage") {
                var boundingRect = backGroupSelected.getBoundingRect();
                var print = parseInt(canobj.get("print"));
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    withinFlag = false;
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                }
                if (withinFlag == true || insideOval == true) {
                    var uploadPrice = 0;
                    var wSizeRangePrice = 0;
                    var hSizeRangePrice = 0;
                    uploadPrice = parseFloat(canobj.get("price"));
                    var obj3 = new Object;
                    obj3.x = boundingRect.left + 35;
                    obj3.y = boundingRect.top;
                    var obj4 = new Object;
                    obj4.x = boundingRect.left + boundingRect.width - 30;
                    obj4.y = boundingRect.top + boundingRect.height;
                    var cloneObj = fabric.util.object.clone(canobj);
                    var withinFlagSizeRange = false;
                    if (objectBoundingRect.left > obj3.x && objectBoundingRect.left + objectBoundingRect.width < obj4.x) {
                        withinFlagSizeRange = true;
                    }
                    if (withinFlagSizeRange == true && print == 0) {
                        angular.forEach(useSizeData, function (sizedata) {
                            if (parseInt(objectBoundingRect.width) > parseInt(sizedata.width)) {
                                wSizeRangePrice = parseFloat(sizedata.widthPrice) ? parseFloat(sizedata.widthPrice) : 0;
                            }
                            if (parseInt(objectBoundingRect.height) > parseInt(sizedata.height)) {
                                hSizeRangePrice = parseFloat(sizedata.heightPrice) ? parseFloat(sizedata.heightPrice) : 0;
                            }
                        });
                        uploadPrice += (wSizeRangePrice + hSizeRangePrice);
                        canobj.set({
                            previousWidthSizePrice: wSizeRangePrice
                        });
                        canobj.set({
                            previousHeightSizePrice: hSizeRangePrice
                        });
                    } else {
                        if (print == 0) {
                            wSizeRangePrice = parseFloat(canobj.get("previousWidthSizePrice")) ? parseFloat(canobj.get("previousWidthSizePrice")) : 0;
                            hSizeRangePrice = parseFloat(canobj.get("previousHeightSizePrice")) ? parseFloat(canobj.get("previousHeightSizePrice")) : 0;
                            uploadPrice += (hSizeRangePrice + wSizeRangePrice);
                        }
                    }
                    var obj = {
                        partName: canobj.get("objTitle"),
                        partPrice: uploadPrice.toFixed(2)
                    };
                    priceInfoArr.push(obj);
                }
            } else if (canobj.get("shapeType") == "Text") {
                var boundingRect = backGroupSelected.getBoundingRect();
                var obj1 = new Object;
                obj1.x = boundingRect.left + 40;
                obj1.y = boundingRect.top;
                var obj2 = new Object;
                obj2.x = boundingRect.left + boundingRect.width - 40;
                obj2.y = boundingRect.top + boundingRect.height;
                var cloneObj = fabric.util.object.clone(canobj);
                var withinFlag = true;
                var insideOval = true;
                var objectBoundingRect = cloneObj.getBoundingRect();
                if (objectBoundingRect.left > obj2.x + 5 || objectBoundingRect.left + objectBoundingRect.width < obj1.x - 5) {
                    insideOval = canobj.get("elementIdentifire") ? true : false;
                    withinFlag = false;
                }
                if (withinFlag == true || insideOval == true) {
                    var txtString = canobj.get("text");
                    var maxTextCharacter = parseInt(canobj.get("maxTextCharacter"));
                    var totalLength = 0;
                    for (var j = 0; j < txtString.length; j++) {
                        if (txtString.charCodeAt(j) != 10 && txtString.charCodeAt(j) != 32) {
                            totalLength++;
                        }
                    }
                    if (totalLength >= 3) {
                        var textTotalPrice = parseFloat(canobj.get("minPrice")) + parseInt(totalLength - maxTextCharacter) * parseFloat(canobj.get("printingCost"));
                    } else {
                        var textTotalPrice = canobj.get("minPrice");
                    }
                    textTotalPrice = parseFloat(textTotalPrice);
                    if (totalLength > 0) {
                        allpartsWithColorArray.push({
                            text: "text",
                            printingType: canobj.get("printingType"),
                            colorId: canobj.get("colorId")
                        });
                        var textPrice = 0;
                        textPrice = parseFloat(textTotalPrice);
                        originalColorArray.push({
                            color: canobj.get("hexColorCode") + textColorCounter,
                            price: textPrice,
                            partName: "color",
                            colorIdentifire: "text",
                            toolLabel: txtString,
                            colorTitle: canobj.get("colorTitle"),
                            partId: canobj.get("colorId")
                        });
                    }
                }
            }
            textColorCounter++;
        });
        for (var i = 0; i < originalColorArray.length - 1; i++) {
            for (var j = 0; j < originalColorArray.length - i - 1; j++) {
                if (originalColorArray[j].price > originalColorArray[j + 1].price) {
                    var swap = originalColorArray[j];
                    originalColorArray[j] = originalColorArray[j + 1];
                    originalColorArray[j + 1] = swap;
                }
            }
        }
        angular.forEach(originalColorArray, function (colorValue, colorKey) {
            currentPartColorObj[colorValue.color] = colorValue;
        });
        angular.forEach(currentPartColorObj, function (value, key) {
            var colorPrice = parseFloat(value.price);
            if (value.colorIdentifire == "text") {
                if (value.partId) {
                    var obj = {
                        partIdendifire: value.partIdendifire,
                        partName: value.toolLabel,
                        partPrice: colorPrice.toFixed(2),
                        colorTitle: value.colorTitle,
                        partId: value.partId,
                        colorIdentifire: value.colorIdentifire,
                        printingType: value.printingType
                    };
                } else {
                    var obj = {
                        partIdendifire: value.partIdendifire,
                        partName: value.toolLabel,
                        partPrice: colorPrice.toFixed(2),
                        colorTitle: value.colorTitle,
                        partId: "",
                        colorIdentifire: value.colorIdentifire,
                        printingType: value.printingType
                    };
                }
                priceInfoArr.push(obj);
            } else {
                if (value.partId) {
                    var obj = {
                        partIdendifire: value.partIdendifire,
                        partName: value.colorTitle,
                        partPrice: colorPrice.toFixed(2),
                        colorTitle: value.colorTitle,
                        partId: value.partId,
                        colorIdentifire: value.colorIdentifire
                    };
                } else {
                    var obj = {
                        partIdendifire: value.partIdendifire,
                        partName: value.colorTitle,
                        partPrice: colorPrice.toFixed(2),
                        colorTitle: value.colorTitle,
                        partId: "",
                        colorIdentifire: value.colorIdentifire
                    };
                }
                priceInfoArr.push(obj);
            }
        });
        angular.forEach(priceInfoArr, function (price) {
            totalPrice += parseFloat(price.partPrice);
        });
        totalPrice = parseFloat(totalPrice).toFixed(2);
        $scope.totalPrice = totalPrice;
        $scope.priceInfoArr = priceInfoArr;
        sendPriceInfoArr = priceInfoArr;
        sendChineseData = allpartsWithColorArray;
        if (!$scope.$$phase) {
            $scope.$apply();
        }
    };
    $scope.checkOptions = function (eventType) {
        var optionsFlag = false;
        var frontCanvas = canvasViewArray[0].canvas;
        frontCanvas.forEachObject(function (canobj) {
            if (canobj.get("shapeType") == "Option") {
                optionsFlag = true;
            }
        });
        if (optionsFlag == true) {
            $scope.buyNow(eventType);
        } else {
            $scope.openPopUp("sizecheck");
        }
    };
    $scope.buyNow = function (eventType) {
        saveEventType = eventType;
        if (eventType == "save") {
            if (customerId != 0) {
                if (mode == "edit") {
                    navigationFlag = true;
                    $scope.openPreloader("Saving..");
                    $scope.saveDataToPhp(eventType);
                } else {
                    navigationFlag = true;
                    $scope.openPreloader("Saving..");
                    $scope.saveDataToPhp(eventType);
                }
            } else {
                if (loginType == "admin") {
                    if (mode == "edit") {
                        navigationFlag = true;
                        $scope.openPreloader("Saving..");
                        $scope.saveDataToPhp(eventType);
                    } else {
                        navigationFlag = true;
                        $scope.openPreloader("Saving..");
                        $scope.saveDataToPhp(eventType);
                    }
                } else {
                    $scope.loginRegister("login");
                }
            }
        } else if (eventType == "loginRegisterSave") {
            if (mode == "edit") {
                $scope.hidePopUp("preloader");
                navigationFlag = false;
                eventType = "save";
                saveEventType = eventType;
                $scope.openPreloader("Saving..");
                $scope.saveDataToPhp(eventType);
            } else {
                navigationFlag = false;
                eventType = "save";
                $scope.openPreloader("Saving..");
                $scope.saveDataToPhp(eventType);
            }
        } else {
            if (mode == "edit") {
                $scope.openPreloader("Buy Now..");
                $scope.saveDataToPhp(eventType);
            } else {
                $scope.openPreloader("Buy Now..");
                $scope.saveDataToPhp(eventType);
            }
        }
    };
    $scope.duplicateDesign = function () {
        preventDuplicateFlag = false;
        $scope.openPopUp("duplicate");
    };
    $scope.saveOrByNow = function (saveTitle) {
        preventDuplicateFlag = saveTitle == "duplicate" ? false : true;
        angular.element("#save_pop_up_duplicate").css({
            display: "none"
        });
        if (saveEventType == "save") {
            $scope.openPreloader("Saving..");
            $scope.saveDataToPhp(saveEventType);
        } else {
            $scope.openPreloader("Buy Now..");
            $scope.saveDataToPhp(saveEventType);
        }
    };
    $scope.formValidation = function () {
        detectArr.push((new Date).getTime());
        if (detectArr.length >= 2) {} else {
            var flag = true;
            userInfo = new Object;
            var customerLoginType = angular.element("#save_pop_up label.c_on").attr("id");
            userInfo.customerType = customerLoginType;
            if (customerLoginType == "register") {
                if (angular.element(".nfirstName").val()) {
                    userInfo.firstName = angular.element(".nfirstName").val();
                } else {
                    flag = false;
                    angular.element(".nfirstName").parent().addClass("has-error");
                }
                if (angular.element(".nlastName").val()) {
                    userInfo.lastName = angular.element(".nlastName").val();
                } else {
                    flag = false;
                    angular.element(".nlastName").parent().addClass("has-error");
                }
                var x = angular.element(".nemail").val();
                if (x) {
                    var atpos = x.indexOf("@");
                    var dotpos = x.lastIndexOf(".");
                    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
                        flag = false;
                        angular.element(".nemail").parent().addClass("has-error");
                    } else {
                        userInfo.email = x;
                    }
                } else {
                    flag = false;
                    angular.element(".nemail").parent().addClass("has-error");
                }
                if (angular.element(".nPass").val()) {} else {
                    flag = false;
                    angular.element(".nPass").parent().addClass("has-error");
                }
                if (angular.element(".ncPass").val()) {} else {
                    flag = false;
                    angular.element(".ncPass").parent().addClass("has-error");
                }
                if (angular.element(".nPass").val() == angular.element(".ncPass").val()) {
                    userInfo.password = angular.element(".nPass").val();
                } else {
                    flag = false;
                    angular.element(".ncPass").parent().addClass("has-error");
                }
                if (flag) {
                    $scope.sendLoginInfo();
                } else {
                    angular.element(".lightbox2").css({
                        display: "block"
                    });
                    angular.element("#save-check-php").css({
                        display: "block"
                    });
                    angular.element(".save-check-message").text("Please Provide Correct Information");
                }
            } else {
                var x = angular.element(".email").val();
                if (x) {
                    var atpos = x.indexOf("@");
                    var dotpos = x.lastIndexOf(".");
                    if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
                        flag = false;
                        angular.element(".email").parent().addClass("has-error");
                    } else {
                        userInfo.email = x;
                    }
                } else {
                    flag = false;
                    angular.element(".email").parent().addClass("has-error");
                }
                if (angular.element(".firstPass").val()) {
                    userInfo.password = angular.element(".firstPass").val();
                } else {
                    flag = false;
                    angular.element(".firstPass").parent().addClass("has-error");
                }
                if (flag) {
                    $scope.sendLoginInfo();
                } else {
                    angular.element(".lightbox2").css({
                        display: "block"
                    });
                    angular.element("#save-check-php").css({
                        display: "block"
                    });
                    angular.element(".save-check-message").text("Please Provide Correct Information");
                }
            }
        }
    };
    $scope.sendLoginInfo = function () {
        angular.element(".lightbox2").css({
            display: "block"
        });
        angular.element("#save-check-php").css({
            display: "block"
        });
        angular.element(".save-check-message").text("Checking Authentication.....");
        var obj = new Object;
        obj.userInfo = userInfo;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/customer", obj, "loginRegister", $scope, userInfo.firstName);
    };
    $scope.sendPreviewArray = function (eventType) {
        var obj = new Object;
        previewArr = new Array;
        angular.forEach(canvasViewArray, function (canobj, i) {
            canvas = canobj.canvas;
            previewArr[i] = new Array;
            previewArr[i] = canvas.toDataURL();
        });
        obj.previewArr = previewArr;
        obj.styleDesignCode = styleDesignCode;
        obj.designId = designId;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/save/preview", obj, "previewArray", $scope, eventType);
    };
    $scope.saveDataToPhp = function (eventType) {
        if (selectedCanvas) {
            selectedCanvas.discardActiveObject();
            selectedCanvas.renderAll();
        }
        $scope.calculateTotalPrice();
        $scope.dataArrays();
        var obj = new Object;
        canvasArr = new Array;
        previewArr = new Array;
        angular.forEach(canvasViewArray, function (canobj, i) {
            canvas = canobj.canvas;
            canvas.discardActiveObject();
            canvas.renderAll();
            canvasArr[i] = new Array;
            previewArr[i] = new Array;
            canvasArr[i] = JSON.stringify(canvas);
            previewArr[i] = canvas.toDataURL();
        });
        obj.sideDataArray = dataArray;
        obj.designDataArray = designDataArr;
        if (preventDuplicateFlag) {
            if (productType && productType == "style") {} else {
                obj.styleDesignCode = styleDesignCode;
                obj.designId = designId;
                obj.mode = mode;
            }
        }
        obj.productId = productId;
        obj.sessionId = sessionId;
        obj.customerId = customerId;
        obj.loginType = loginType;
        obj.priceInfoArr = sendPriceInfoArr;
        obj.chineseInfoArr = sendChineseData;
        obj.partDropDownArr = angular.toJson(partDropDownArr);
        obj.totalPrice = totalPrice;
        obj.eventType = eventType;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/save", obj, "buyNow", $scope, eventType);
    };
}]);

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev, str, obj) {
    if (str == "clip" || str == "upload") {
        loadingPreventFlag = str;
        dragThumb = $(obj).attr("thumb");
        dragColorable = $(obj).attr("colorable");
        dragPrice = $(obj).attr("price");
        dragColorCode = $(obj).attr("clipcolor");
        dragTitle = $(obj).attr("cliplabel");
        dragOptionId = $(obj).attr("clipId");
        var src = $(obj).find("img").attr("src");
        ev.dataTransfer.setData("Text", src);
    } else if (str == "option") {
        loadingPreventFlag = str;
        dragThumb = $(obj).attr("thumb");
        optionId = $(obj).attr("optionId");
        optionColorPrice = $(obj).attr("colorprice");
        dragColorable = $(obj).attr("colorable");
        dragPrice = $(obj).attr("price");
        dragColorTitle = $(obj).attr("colorTitle");
        dragColorId = $(obj).attr("colorId");
        dragColorCode = $(obj).attr("clipcolor");
        dragTitle = $(obj).attr("cliplabel");
        var src = $(obj).find("img").attr("src");
        ev.dataTransfer.setData("Text", src);
    } else if (str == "parts") {
        loadingPreventFlag = str;
        dragPartStId = $(ev.target).attr("partStId");
        dragPartName = $(ev.target).attr("partStName");
        dragPartIden = $(ev.target).attr("partIden");
        dragPartPrice = $(ev.target).attr("partPrice");
        var src = $(ev.target).css("background");
        ev.dataTransfer.setData("Text", src);
    }
}

function drop(ev) {
    if (ev) {
        ev.preventDefault();
    }
    var allow = 0;
    var pageX = ev.pageX;
    var pageY = ev.pageY;
    var arr = $(".canvas-case-inner div.canvas-container");
    var position = $(arr[nextCounter]).offset();
    var can_left = position.left;
    var can_top = position.top;
    var can_width = canWidth;
    var can_height = canHeight;
    var l = parseInt(can_left) + parseInt(can_width);
    var t = parseInt(can_top) + parseInt(can_height);
    allow = pageX > can_left && pageX < l ? pageY > can_top && pageY < t ? 1 : 0 : 0;
    if (loadingPreventFlag && allow == 1) {
        if (loadingPreventFlag == "clip") {
            var scope = angular.element("#clipartsview").scope();
            scope.addCliparts(dragThumb, dragColorable, dragPrice, dragColorCode, dragTitle, dragOptionId);
        } else if (loadingPreventFlag == "option") {
            var scope = angular.element(".root-controller").scope();
            scope.addOptions(optionId, dragThumb, dragColorable, dragPrice, dragColorCode, dragTitle, optionColorPrice, dragColorTitle, dragColorId);
        } else if (loadingPreventFlag == "upload") {
            var scope = angular.element("#clipartsview").scope();
            scope.addUploadedImage(dragThumb, dragTitle);
        } else if (loadingPreventFlag == "parts") {
            var scope = angular.element(".root-controller").scope();
            scope.loadPartLayersInfoByCode(dragPartStId, dragPartName, dragPartIden, dragPartPrice);
        }
        loadingPreventFlag = null;
    }
}

function set_drop(upId) {
    var allow = 0;
    var arr = $(".canvas-case-inner div.canvas-container");
    var position = $(arr[0]).offset();
    var can_left = position.left;
    var can_top = position.top;
    var can_width = canvas.width;
    var can_height = canvas.height;
    var l = parseInt(can_left) + parseInt(can_width);
    var t = parseInt(can_top) + parseInt(can_height);
    allow = psX > can_left && psX < l ? psY > can_top && psY < t ? 1 : 0 : 0;
    if (allow == 1) {
        var imgid = "#" + upId;
        psX = 0;
        psY = 0;
    }
}

function getJSON1(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[0].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON2(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[1].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON3(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[2].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON4(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[3].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON5(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[4].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON6(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[5].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON7(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[6].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function getJSON8(url) {
    var deffer = $.Deferred();
    if (url == "imageUrl") {
        var arr = new Array;
        for (var layerCounter = 0; layerCounter < selectedPartLayersInfo.layers.length; layerCounter++) {
            var imageSrc = layersPath + selectedPartLayersInfo.partStyleCode + "/" + selectedPartLayersInfo.layers[layerCounter].layerCode + productImageRatio + anglesData[7].name + ".png";
            arr.push(imageSrc);
        }
        return $.Deferred(function (deffer) {
            deffer.resolve(arr);
        });
    } else {
        return $.Deferred(function (deffer) {
            fabric.Image.fromURL(url, function (img) {
                deffer.resolve(img);
            });
        });
    }
}

function loadImagesOnCanvas2() {
    if (editMode == "edit" && editSelectedPartLayersInfo) {
        angular.forEach(editSelectedPartLayersInfo, function (editinfo, key) {
            if (groupTitle == "Body") {
                if (editinfo.layerName == "LAYER8") {
                    singleStichingColor = editinfo.hexColorCode;
                    singleStichingColorID = editinfo.colorId;
                    singleStichingColorTitle = editinfo.colorTitle;
                }
            }
        });
    } else {
        angular.forEach(selectedPartLayersInfo.layers, function (layerInfo, key) {
            if (groupTitle == "Body") {
                if (layerInfo.layerCode == "LAYER8") {
                    singleStichingColor = layerInfo.defaultColorCode;
                    singleStichingColorID = layerInfo.defaultColorId;
                    singleStichingColorTitle = layerInfo.defaultColorTitle;
                }
            }
        });
    }
    globalViewCounter = 0;
    fabricImageObjects1 = new Array;
    fabricImageObjects2 = new Array;
    fabricImageObjects3 = new Array;
    fabricImageObjects4 = new Array;
    fabricImageObjects5 = new Array;
    fabricImageObjects6 = new Array;
    fabricImageObjects7 = new Array;
    fabricImageObjects8 = new Array;
    img1[0] = new Array;
    img2[0] = new Array;
    img3[0] = new Array;
    img4[0] = new Array;
    img5[0] = new Array;
    img6[0] = new Array;
    img7[0] = new Array;
    img8[0] = new Array;
    angular.forEach(anglesData, function (data, key) {
        if (key == 0) {
            loadImages1();
        } else if (key == 1) {
            loadImages2();
        } else if (key == 2) {
            loadImages3();
        } else if (key == 3) {
            loadImages4();
        } else if (key == 4) {
            loadImages5();
        } else if (key == 5) {
            loadImages6();
        } else if (key == 6) {
            loadImages7();
        } else if (key == 7) {
            loadImages8();
        }
    });
}

function loadImages1() {
    getJSON1("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON1).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects1.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects1.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img1[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[0].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[0].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[0].canvas;
        var grpObj = new fabric.Group(img1[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (groupTitle == "Body") {
            var circle = new fabric.Circle({
                radius: 50,
                fill: "",
                stroke: "#666666",
                strokeWidth: 2,
                scaleY: 0.5,
                originX: "center",
                originY: "center"
            });
            var text = new fabric.Text("TOP OF LID", {
                fontSize: 15,
                top: -35,
                color: "#666666",
                fill: "#666666",
                originX: "center",
                originY: "center"
            });
            var group = new fabric.Group([circle, text], {
                left: 0,
                top: 105,
                shapeType: "topGroup",
                selectable: false,
                angle: 0
            });
            toplidGrp = group;
            canvas.add(group);
            canvas.renderAll();
            var circle = new fabric.Circle({
                radius: 50,
                fill: "",
                stroke: "#666666",
                strokeWidth: 2,
                scaleY: 0.5,
                originX: "center",
                originY: "center"
            });
            var text = new fabric.Text("INSIDE OF LID", {
                fontSize: 15,
                top: -35,
                color: "#666666",
                fill: "#666666",
                originX: "center",
                originY: "center"
            });
            var group1 = new fabric.Group([circle, text], {
                left: 0,
                top: 180,
                shapeType: "insideGroup",
                selectable: false,
                angle: 0
            });
            insidelidGrp = group1;
            canvas.add(group1);
            canvas.renderAll();
            frontGroupSelected = grpObj;
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {}
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages2() {
    var deffere = $.Deferred();
    getJSON2("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON2).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects2.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects2.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img2[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[1].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER8" || layerCode == "LAYER6")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[1].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[1].canvas;
        var grpObj = new fabric.Group(img2[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (1 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (1 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages3() {
    getJSON3("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON3).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects3.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects3.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img3[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[2].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER8" || layerCode == "LAYER6")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[2].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[2].canvas;
        var grpObj = new fabric.Group(img3[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (groupTitle == "Double Shoulder Pad") {}
        if (2 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (2 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages4() {
    getJSON4("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON4).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects4.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects4.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img4[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[3].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER8" || layerCode == "LAYER6")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[3].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[3].canvas;
        var grpObj = new fabric.Group(img4[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (3 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (3 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {}
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages5() {
    getJSON5("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON5).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects5.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects5.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img5[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[4].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[4].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[4].canvas;
        var grpObj = new fabric.Group(img5[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (4 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (4 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages6() {
    getJSON6("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON6).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects6.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects6.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img6[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[5].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[5].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[5].canvas;
        var grpObj = new fabric.Group(img6[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (5 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (5 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages7() {
    getJSON7("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON7).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects7.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects7.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img7[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[6].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[6].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[6].canvas;
        var grpObj = new fabric.Group(img7[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (6 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (6 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function loadImages8() {
    getJSON8("imageUrl").then(function (viewImageList) {
        return viewImageList.map(getJSON8).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (imageObj) {
                fabricImageObjects8.push(imageObj);
            });
        }, $.Deferred().resolve());
    }).then(function () {
        fabricImageObjects8.forEach(function (img, layerCounter) {
            if (editMode == "edit" && groupTitle == "Pocket" && editSelectedPartLayersInfo[layerCounter]) {
                var width = img.width;
                var height = img.height;
                var left = parseFloat(editSelectedPartLayersInfo[0].left);
                var top = parseFloat(editSelectedPartLayersInfo[0].top);
                imageLeft = left;
                imageTop = top;
            } else {
                var width = img.width;
                var height = img.height;
                var left = (canWidth - width) / 2;
                var top = (canHeight - height) / 2;
                if (selectedPartLayersInfo.partStyleCode == "PK3") {
                    top = 85;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK6") {
                    top = 158;
                }
                if (selectedPartLayersInfo.partStyleCode == "PK10") {
                    top = 270;
                }
                partLeftPos = left;
                partTopPos = top;
                imageLeft = left;
                imageTop = top;
            }
            img8[0][layerCounter] = img.set({
                perPixelTargetFind: true,
                selectable: false,
                partStyleCode: selectedPartLayersInfo.partStyleCode,
                layerCode: selectedPartLayersInfo.layers[layerCounter].layerCode,
                angleName: anglesData[7].name,
                partName: selectedPartLayersInfo.layers[layerCounter].layerCode,
                width: width,
                height: height
            });
            if (editMode == "edit" && editSelectedPartLayersInfo[layerCounter]) {
                var color = editSelectedPartLayersInfo[layerCounter].hexColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER6" || layerCode == "LAYER8")) {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].hexColorCode = singleStichingColor;
                    editSelectedPartLayersInfo[layerCounter].colorId = singleStichingColorID;
                    editSelectedPartLayersInfo[layerCounter].colorTitle = singleStichingColorTitle;
                    editSelectedPartLayersInfo[layerCounter].styleCode = selectedPartLayersInfo.partStyleCode;
                }
            } else {
                var color = selectedPartLayersInfo.layers[layerCounter].defaultColorCode;
                var layerCode = selectedPartLayersInfo.layers[layerCounter].layerCode;
                if (groupTitle == "Body" && (layerCode == "LAYER8" || layerCode == "LAYER12" || layerCode == "LAYER14")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if ((groupTitle == "ShoulderPad" || groupTitle == "Double Shoulder Pad") && (layerCode == "LAYER8" || layerCode == "LAYER6")) {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "TOP" && layerCode == "LAYER4") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                } else if (selectedPartLayersInfo.partStyleCode == "SIDE" && layerCode == "Layer5") {
                    color = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorCode = singleStichingColor;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorId = singleStichingColorID;
                    selectedPartLayersInfo.layers[layerCounter].defaultColorTitle = singleStichingColorTitle;
                }
            }
            if (color) {
                canvas = canvasViewArray[7].canvas;
                var arr = hex2Rgb("0x" + color);
                global_SelectedItem = img;
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
            }
        });
        if (groupTitle == "Pocket") {
            var lockMovementX = true;
            var lockMovementY = false;
        } else {
            var lockMovementX = true;
            var lockMovementY = true;
        }
        canvas = canvasViewArray[7].canvas;
        var grpObj = new fabric.Group(img8[0], {
            perPixelTargetFind: true,
            shapeType: "grp",
            lockMovementX: lockMovementX,
            lockMovementY: lockMovementY,
            groupTitle: groupTitle,
            selectable: true,
            partName: partTitle,
            left: imageLeft,
            top: imageTop,
            hasControls: false,
            hasBorders: false
        });
        canvas.add(grpObj);
        canvas.renderAll();
        if (7 == canvasViewArray.length / 2) {
            if (groupTitle == "Body") {
                backGroupSelected = grpObj;
            }
        }
        if (4 == canvasViewArray.length / 2) {
            if (groupTitle == "Double Shoulder Pad") {
                grpObj.set({
                    scaleX: 0.8,
                    left: grpObj.getLeft() - 5
                });
                canvas.renderAll();
                var cloneObj = fabric.util.object.clone(grpObj);
                cloneObj.set({
                    left: cloneObj.getLeft() + 45,
                    angle: 3
                });
                canvas.add(cloneObj);
                canvas.renderAll();
            }
        }
        if (groupTitle == "Double Shoulder Pad") {}
        if (viewCounter == 0 && (groupTitle == "Pocket" || groupTitle == "Handle")) {
            if (editMode != "edit") {
                saveState(grpObj, CHANGETYPE_ADD);
            }
        }
        globalViewCounter++;
        manageOtherLayerAfter8View();
    }).then(function () {});
}

function manageOtherLayerAfter8View() {
    if (globalViewCounter == canvasViewArray.length) {
        outputCan.renderAll();
        var scope = angular.element(".root-controller").scope();
        if (scope) {
            if (editMode == "edit") {
                editCounter++;
                scope.manageLayersInEditMode();
            } else {
                scope.manageLayersAndTitle();
            }
        }
    }
}

function get(url) {
    return $.Deferred(function (deffer) {
        $.ajax({
            method: "GET",
            url: url,
            success: function (data) {
                deffer.resolve(data);
            }
        });
    });
}

function getJSON(url) {
    if (url == "cliparts") {
        promiseDataArr = new Array;
        promiseDataArr.push(dataAccessUrl + "designersoftware/clipart/category");
        promiseDataArr.push(dataAccessUrl + "designersoftware/upload/images");
        promiseDataArr.push(dataAccessUrl + "designersoftware/clipart/color");
        return $.Deferred(function (deffer) {
            deffer.resolve(promiseDataArr);
        });
    } else if (url == "tooldata") {
        return $.Deferred(function (deffer) {
            deffer.resolve(promiseDataArr);
        });
    } else {
        return get(url).then(JSON);
    }
}

function loadDataByPromise(section) {
    getJSON(section).then(function (promiseArr) {
        return promiseArr.map(getJSON).reduce(function (sequence, imagePromise) {
            return sequence.then(function () {
                return imagePromise;
            }).then(function (data) {
                data = angular.fromJson(data);
                promiseDataArrCounter++;
                if (section == "cliparts") {
                    if (promiseDataArrCounter == 1) {
                        var sc = angular.element("#clipartsview").scope();
                        sc.updateClipartCats(data);
                    }
                    if (promiseDataArrCounter == 2) {
                        var sc = angular.element("#clipartsview").scope();
                        sc.updateUploadedImages(data);
                    }
                    if (promiseDataArrCounter == 3) {
                        var sc = angular.element("#clipartsview").scope();
                        sc.updateClipartColors(data);
                    }
                } else {
                    if (promiseDataArrCounter == 1) {
                        var sc = angular.element("#textsview").scope();
                        sc.updateFonts(data);
                    }
                    if (promiseDataArrCounter == 2) {
                        var sc = angular.element("#textsview").scope();
                        sc.updateTextColors(data);
                    }
                    if (promiseDataArrCounter == 3) {
                        var sc = angular.element(".root-controller").scope();
                        anglesData = data;
                        sc.makeCanvasObjcts();
                    }
                    if (promiseDataArrCounter == 4) {
                        var sc = angular.element(".root-controller").scope();
                        sc.loadParts(data);
                    }
                    if (promiseDataArrCounter == 5) {
                        if (!optionsData) {
                            var prscope = angular.element("#stylesview").scope();
                            prscope.optionsData = data.sizes;
                            prscope.$apply();
                        }
                    }
                    if (promiseDataArrCounter == 6) {
                        printings = data.printings;
                        var thumb = "";
                        angular.forEach(printings, function (data, i) {
                            thumb += "<option   value=\"printing\">" + data.title + "</option>";
                        });
                        angular.element(".printing-select").parent().css({
                            display: "block"
                        });
                        angular.element(".color_text ul").css({
                            maxHeight: "100px"
                        });
                        angular.element(".country_id3").html(thumb);
                        $(".country_id3").selectbox();
                    }
                    if (promiseDataArrCounter == 7) {
                        useSizeData = data.sizeRange;
                    }
                }
            });
        }, $.Deferred().resolve());
    }).then(function () {}).then(function () {});
}
