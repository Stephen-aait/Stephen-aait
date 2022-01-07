controllersModule.controller("ClipartsController", ["$scope", "webServices", "$http", function ($scope, webServices, $http) {
    $scope.clipartcolorableFlag = false;
    angular.element("#UploadForm button.customfile-upload").addClass("btn");
    angular.element("#UploadForm button.customfile-upload").text("Upload Photo");
    $scope.loadClipArtsByCatId = function (catid) {
        if (clipartsCat) {
            $scope.openPreloader("Loading Clipart Data");
        }
        var obj = new Object;
        obj.clipartCategoryId = catid;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/clipart", obj, "cliparts", $scope);
    };
    $scope.loadClipartColors = function () {
        var obj = new Object;
        webServices.customServerCall($http, dataAccessUrl + "designersoftware/clipart/color", null, "cliartColors", $scope);
    };
    $scope.changeClipartColor = function (color, indx, colorPrice, colorTitle, colorId) {
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
            global_SelectedItem = selectedCanvas.getActiveObject();
        }
        if (global_SelectedItem && global_SelectedItem.get("shapeType") == "Clipart") {
            if (global_SelectedItem.get("colorable") == 1) {
                clipColorId = colorId, artColorSelectedIndx = indx;
                clipColorPrice = colorPrice;
                clipColorTitle = colorTitle;
                angular.element(".art-color ul li a").removeClass("active");
                var childs = angular.element(".art-color ul li a");
                angular.element(childs[artColorSelectedIndx]).attr("class", "active");
                var arr = hex2Rgb("0x" + color);
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: color,
                    colorPrice: clipColorPrice,
                    colorId: clipColorId,
                    artColorSelectedIndx: artColorSelectedIndx,
                    colorTitle: clipColorTitle
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(selectedCanvas.renderAll.bind(selectedCanvas));
                if (canvasSelectionFlag == "design") {
                    saveState(global_SelectedItem, CHANGETYPE_UPDATE);
                }
                $scope.calculateTotalPrice();
                if (mobileMode) {
                    $(".tab-content").css({
                        display: "none"
                    });
                }
            } else {
                alert("This Image is not colorable?");
            }
        }
    };
    $scope.addCliparts = function (thumb, colorable, price, color, label, optionId, print) {
        $scope.openPreloader();
        var httpUrl = clipArtPath + thumb;
        httpUrl = httpUrl.replace("/224X224/", "/original/");
        angular.forEach(clipartColors, function (obj, i) {
            if (obj.colorCode == defaultClipColor) {
                artColorSelectedIndx = i;
            }
        });
        fabric.Image.fromURL(httpUrl, function (img) {
            var cW = 73;
            var cH = 440;
            var oW = img.width;
            var oH = img.height;
            var cR = cW / cH;
            var oR = oW / oH;
            var nW = 20;
            var nH = 20;
            if (oW < 73 && oH < 440) {
                nW = oW;
                nH = oH;
            } else if (cR > oR) {
                nH = cH;
                nW = oW * nH / oH;
            } else {
                nW = cW;
                nH = nW * oH / oW;
            }
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
            var left = (canWidth - nW) / 2;
            var top = (canHeight - nH) / 2;
            canvas = canvasViewArray[currentView].canvas;
            selectedCanvas = canvas;
            img.set({
                name: "Clipart",
                left: left,
                width: nW,
                height: nH,
                top: top,
                previousHeightSizePrice: 0,
                previousWidthSizePrice: 0,
                optionId: optionId,
                print: print,
                shapeType: "Clipart",
                price: price,
                objTitle: label,
                colorTitle: clipColorTitle,
                colorPrice: clipColorPrice,
                artColorSelectedIndx: artColorSelectedIndx,
                colorable: colorable,
                thumbSrc: thumb
            });
            if (colorable == 1) {
                $scope.clipartcolorableFlag = true;
                angular.element(".art-color ul li a").removeClass("active");
                var childs = angular.element(".art-color ul li a");
                angular.element(childs[artColorSelectedIndx]).attr("class", "active");
                global_SelectedItem = img;
                var arr = hex2Rgb("0x" + defaultClipColor);
                colorPickerValue = "rgb(" + arr[0] + "," + arr[1] + "," + arr[2] + ")";
                global_SelectedItem.set({
                    hexColorCode: defaultClipColor,
                    colorId: clipColorId
                });
                global_SelectedItem.fill = colorPickerValue;
                colorPickerValue = colorPickerValue;
                global_SelectedItem.filters[1] = new fabric.Image.filters.Invert;
                global_SelectedItem.applyFilters(selectedCanvas.renderAll.bind(selectedCanvas));
            } else {
                $scope.clipartcolorableFlag = false;
            }
            img.setCoords();
            selectedCanvas.renderAll();
            canvas.setActiveObject(img);
            canvas.add(img);
            canvas.renderAll();
            saveState(img, CHANGETYPE_ADD);
            setTimeout(function () {
                var cloneObj = fabric.util.object.clone(img);
                $scope.calculateTotalPrice();
                $scope.hidePopUp("preloader");
            }, 200);
            if (mobileMode) {
                $(".tab-content").css({
                    display: "none"
                });
            }
        });
    };
    $scope.uploadPage1Open = function () {
        angular.element(".upload_photo_box").css({
            display: "none"
        });
        angular.element(".art_upload_section").css({
            display: "block"
        });
        angular.element(".art-view-section").css({
            display: "none"
        });
    };
    $scope.uploadPage2Open = function () {
        angular.element(".upload_photo_box").css({
            display: "block"
        });
        angular.element(".art_upload_section").css({
            display: "none"
        });
        angular.element(".art-view-section").css({
            display: "none"
        });
    };
    $scope.uploadCancel = function () {
        angular.element(".upload_photo_box").css({
            display: "none"
        });
        angular.element(".art_upload_section").css({
            display: "none"
        });
        angular.element(".art-view-section").css({
            display: "block"
        });
    };
    $scope.updateUploadImage = function () {
        $scope.uploadImageArr = uploadImageArr;
        if (!$scope.$$phase) {
            $scope.$apply();
        }
    };
    $scope.addUploadedImage = function (thumbSrc, label) {
        $scope.openPreloader();
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
        var httpUrl = uploadLarge + thumbSrc;
        httpUrl = httpUrl.replace("/224X224/", "/original/");
        fabric.Image.fromURL(httpUrl, function (img) {
            var cW = 73;
            var cH = 440;
            var oW = img.width;
            var oH = img.height;
            var cR = cW / cH;
            var oR = oW / oH;
            var nW = 20;
            var nH = 20;
            if (oW < 73 && oH < 440) {
                nW = oW;
                nH = oH;
            } else if (cR > oR) {
                nH = cH;
                nW = oW * nH / oH;
            } else {
                nW = cW;
                nH = nW * oH / oW;
            }
            var left = (canWidth - nW) / 2;
            var top = (canHeight - nH) / 2;
            canvas = canvasViewArray[currentView].canvas;
            selectedCanvas = canvas;
            img.set({
                name: "UploadImage",
                left: left,
                width: nW,
                height: nH,
                top: top,
                previousHeightSizePrice: 0,
                previousWidthSizePrice: 0,
                shapeType: "UploadImage",
                price: uploadPrice,
                objTitle: label,
                thumbSrc: thumbSrc
            });
            canvas.add(img);
            var cloneObj = fabric.util.object.clone(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
            saveState(img, CHANGETYPE_ADD);
            $scope.calculateTotalPrice();
            $scope.hidePopUp("preloader");
            if (mobileMode) {
                $(".tab-content").css({
                    display: "none"
                });
            }
        });
    };
    $scope.updateClipartCats = function (data) {
        clipartsCat = data.cats;
        $scope.clipartsCat = clipartsCat;
        var thumb = "";
        angular.forEach(clipartsCat, function (data, i) {
            thumb += "<option class=\"clipart\" onclick=\"changeCliparts(this)\" value=\"clipart\">" + data.title + "</option>";
        });
        angular.element(".country_id").html(thumb);
        $(".country_id").selectbox();
        var sc = angular.element("#clipartsview").scope();
        angular.element(".art_select").css({
            display: "block"
        });
    };
    $scope.updateClipartColors = function (data) {
        $scope.clipartColors = data.colors;
        clipartColors = data.colors;
        if (defaultClipColor == "") {
            defaultClipColor = clipartColors[0].colorCode;
            clipColorPrice = clipartColors[0].price;
            clipColorId = clipartColors[0].id;
            clipColorTitle = clipartColors[0].title;
        }
        $scope.loadClipArtsByCatId(clipartsCat[0].id);
    };
    $scope.updateUploadedImages = function (data) {
        if (data.uploadedImage) {
            uploadImageArr = data.uploadedImage;
            $scope.updateUploadImage();
        }
    };
}]);
