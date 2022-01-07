angular.element(document).ready(function () {
    $(".preloader, .lightbox").fadeIn(300);
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        fabric.Object.prototype.cornerSize = 42;
    }
    previewCanvas = new fabric.Canvas("previewCanvas");
    previewCanvas.selection = false;
    previewCanvas.setWidth(previewWidth);
    previewCanvas.setHeight(previeHeight);
    previewCanvas.renderAll();
    outputCan = new fabric.Canvas("outputCan", {
        controlsAboveOverlay: true
    });
    outputCan.renderAll();
    outputCan.selection = false;
    var prevPtag = $(".col-main").children("p");
    $.each(prevPtag, function (key, ele) {
        $(ele).css({
            margin: "0px"
        });
    });
    if (mode == "edit") {
        if (productType && productType == "style") {
            angular.element(".duplicate-design").css({
                display: "none"
            });
        } else {
            angular.element(".duplicate-design").css({
                display: "block"
            });
        }
    } else {
        angular.element(".duplicate-design").css({
            display: "none"
        });
    }
    if (loginType == "admin") {
        angular.element(".login-register-a").css({
            display: "none"
        });
        angular.element(".welcome-register").css({
            display: "none"
        });
        angular.element(".buy-now-action").addClass("customdisabled");
    } else {
        if (customerId) {
            angular.element(".login-register-a").css({
                display: "none"
            });
            angular.element(".welcome-register").css({
                display: "block"
            });
        } else {
            angular.element(".login-register-a").css({
                display: "block"
            });
            angular.element(".welcome-register").css({
                display: "none"
            });
        }
    }
    if (window.innerWidth < 767) {
        smallMobileDeviceFlag = true;
    } else {
        smallMobileDeviceFlag = false;
    }
    if (window.innerWidth <= 1024) {
        mobileMode = true;
        $(".tab-content").css({
            display: "none"
        });
        $(".nav-tabs li").removeClass("active");
        $(".remove-selected-obj").removeClass("pull-left");
        $(".remove-selected-obj").removeClass("pull-right");
    } else {
        $(".remove-selected-obj").removeClass("pull-left");
        $(".remove-selected-obj").removeClass("pull-right");
        $(".remove-selected-obj").addClass("pull-right");
        mobileMode = false;
    }
    if (!mobileMode) {
        var wheelholdercantainer = document.getElementById("wheelEventContainer");
        var mousewheelevt = /Firefox/i.test(navigator.userAgent) ? "DOMMouseScroll" : "mousewheel";
        if (wheelholdercantainer.attachEvent) {
            wheelholdercantainer.attachEvent("on" + mousewheelevt, rotateProductIn3D);
        } else if (wheelholdercantainer.addEventListener) {
            wheelholdercantainer.addEventListener(mousewheelevt, rotateProductIn3D, false);
        }
    } else {
        document.getElementById("wheelEventContainer").addEventListener("touchstart", function (event) {
            var touch = event.touches[0];
            dragX = touch.pageX;
            if (!objPreventFlag) {
                document.addEventListener("touchmove", rotationOnDrag);
                document.addEventListener("touchend", removeRotationOnDrag);
            }
        });
    }
    angular.element(".upload_photo_box").css({
        display: "none"
    });
    angular.element(".art_upload_section").css({
        display: "none"
    });
    angular.element(".root-controller").on("keyup", "#textArea", function () {
        var scope = angular.element("#textsview").scope();
        scope.updateText("update");
    });
    angular.element(".root-controller").on(clickEventType, ".nav-tabs li", function () {
        var scope = angular.element(this).scope();
        scope.changeView(angular.element(this).index(), false);
    });
    document.getElementById("orderInfo").addEventListener("touchstart", function (event) {
        var touch = event.touches[0];
        var x = touch.pageX - angular.element(".root-controller").offset().left - angular.element(".info-order").width();
        var y = touch.pageY + 30;
        var display = angular.element(".info-order").css("display") == "block" ? "none" : "block";
        angular.element(".info-order").css({
            display: display,
            left: x + "px",
            top: y + "px"
        });
    });
    angular.element(".root-controller").on(onderInfoEvent, ".order-info-popUp", function (event) {
        if (clickEventType == "click") {
            var x = event.pageX - angular.element(".root-controller").offset().left - angular.element(".info-order").width() + 20;
            var y = event.pageY - angular.element(".root-controller").offset().top - 10;
            angular.element(".info-order").css({
                display: "block",
                left: x + "px",
                top: y + "px"
            });
        }
    });
    angular.element(".root-controller").on("mouseleave", ".order-info-popUp", function () {});
    angular.element(".root-controller").on("touchstart", ".order-info-popUp", function (event) {});
    angular.element(".root-controller").on("mouseenter", ".info-order", function (event) {
        angular.element(".info-order").css({
            display: "block"
        });
    });
    angular.element(".root-controller").on("mouseleave", ".info-order", function (event) {
        angular.element(".info-order").css({
            display: "none"
        });
    });
    angular.element(".root-controller").on("mouseleave", "#save_pop_up div.form-group input", function (event) {
        angular.element(this).parent().removeClass("has-error");
    });
    angular.element(".root-controller").on("mousemove", ".partlayercolorholder", function (event) {
        if (xinterVal) {
            clearTimeout(xinterVal);
        }
        xinterVal = setTimeout(function () {
            startInteractionTimer(event);
        }, 800);
    });
    angular.element(".root-controller").on("mousemove", ".order-wrapper", function (event) {
        leaveColorTextureFlag = false;
        var x = event.pageX - angular.element(".root-controller").offset().left;
        var y = event.pageY - angular.element(".root-controller").offset().top;
        var boundaryX = angular.element(".partlayercolorholder").offset().left - angular.element(".root-controller").offset().left;
        var boundaryY = angular.element(".partlayercolorholder").offset().top - angular.element(".root-controller").offset().top;
        var boundaryFlag = false;
        if (x >= boundaryX && x <= boundaryX + angular.element(".partlayercolorholder").width()) {
            if (y >= boundaryY && y <= boundaryY + angular.element(".partlayercolorholder").height()) {
                leaveColorTextureFlag = true;
            }
        }
        if (leaveColorTextureFlag == false) {
            angular.element(".color-texture-holder").css({
                display: "none"
            });
            preColorSelectedItem = "";
        }
    });
    angular.element(".root-controller").on("mouseleave", ".partlayercolorholder", function (event) {});
    angular.element(".root-controller").on("mouseenter", ".partlayercolorholder li", function (event) {
        if (clickEventType == "click") {
            var x = angular.element(this).offset().left - angular.element(".root-controller").offset().left + 20;
            var y = angular.element(this).offset().top - angular.element(".root-controller").offset().top;
            var width = angular.element(".root-controller").width();
            var height = angular.element(".root-controller").height();
            if (x + angular.element(".color-texture-holder").width() > width) {
                x = angular.element(this).offset().left - angular.element(".root-controller").offset().left - angular.element(".color-texture-holder").width();
            }
            if (y + angular.element(".color-texture-holder").height() > height) {
                var decrH = y + angular.element(".color-texture-holder").height() - height;
                y = y - decrH;
            }
            var texture = angular.element(this).attr("texture");
            if (texture) {
                angular.element(".color-texture-holder").find("div").css({
                    backgroundColor: ""
                });
                var colortitle = angular.element(this).attr("colortitle");
                var texturetitle = angular.element(this).attr("texturetitle");
                angular.element(".color-texture-holder").find("img").css({
                    display: "block"
                });
                angular.element(".color-texture-holder").find("img").attr("src", colorTexturePath + texture);
                angular.element(".color-texture-holder").find("div").css({
                    backgroundColor: "#" + colorcode
                });
                angular.element(".color-texture-holder").find("span").text(texturetitle);
            } else {
                angular.element(".color-texture-holder").find("img").css({
                    display: "none"
                });
                var colorcode = angular.element(this).attr("colorcode");
                var colortitle = angular.element(this).attr("colortitle");
                angular.element(".color-texture-holder").find("div").css({
                    backgroundColor: "#" + colorcode
                });
                angular.element(".color-texture-holder").find("span").text(colortitle);
            }
        }
    });
    angular.element(".root-controller").on("touchstart", ".partlayercolorholder li", function (event) {
        var x = angular.element(this).offset().left - angular.element(".root-controller").offset().left + 2;
        var y = angular.element(this).offset().top - angular.element(".root-controller").offset().top;
        var width = angular.element(".root-controller").width();
        var height = angular.element(".root-controller").height();
        if (x + angular.element(".color-texture-holder").width() > width) {
            x = angular.element(this).offset().left - angular.element(".root-controller").offset().left - angular.element(".color-texture-holder").width();
        }
        if (y + angular.element(".color-texture-holder").height() > height) {
            var decrH = y + angular.element(".color-texture-holder").height() - height;
            y = y - decrH;
        }
        var texture = angular.element(this).attr("texture");
        if (texture) {
            angular.element(".color-texture-holder").find("div").css({
                backgroundColor: ""
            });
            var colortitle = angular.element(this).attr("colortitle");
            var texturetitle = angular.element(this).attr("texturetitle");
            angular.element(".color-texture-holder").find("img").css({
                display: "block"
            });
            angular.element(".color-texture-holder").find("img").attr("src", colorTexturePath + texture);
            angular.element(".color-texture-holder").find("div").css({
                backgroundColor: "#" + colorcode
            });
            angular.element(".color-texture-holder").find("span").text(texturetitle);
        } else {
            angular.element(".color-texture-holder").find("img").css({
                display: "none"
            });
            var colorcode = angular.element(this).attr("colorcode");
            var colortitle = angular.element(this).attr("colortitle");
            angular.element(".color-texture-holder").find("div").css({
                backgroundColor: "#" + colorcode
            });
            angular.element(".color-texture-holder").find("span").text(colortitle);
        }
        angular.element(".color-texture-holder").css({
            display: "block",
            left: x + "px",
            top: y + "px"
        });
    });
    angular.element(".root-controller").on("mouseleave", ".color-texture-holder", function (event) {
        angular.element(".color-texture-holder").css({
            display: "none"
        });
        preColorSelectedItem = "";
    });
    angular.element(".root-controller").on("mouseleave", ".partlayercolorholder li", function (event) {
        if (clickEventType == "click") {
            var x = event.pageX - angular.element(".root-controller").offset().left;
            var y = event.pageY - angular.element(".root-controller").offset().top;
            var popupX = angular.element(".color-texture-holder").offset().left - angular.element(".root-controller").offset().left;
            var popupY = angular.element(".color-texture-holder").offset().top - angular.element(".root-controller").offset().top;
            var showFlag = false;
            if (x >= popupX && x <= popupX + angular.element(".color-texture-holder").width()) {
                if (y >= popupY && y <= popupY + angular.element(".color-texture-holder").height()) {
                    showFlag = true;
                }
            }
            if (showFlag == false) {
                preColorSelectedItem = null;
                angular.element(".color-texture-holder").css({
                    display: "none"
                });
            }
        }
    });
    angular.element(".root-controller").on("touchend", ".partlayercolorholder li", function (event) {
        var touch = event.touches[0];
        var x = touch.pageX - angular.element(".root-controller").offset().left;
        var y = touch.pageY - angular.element(".root-controller").offset().top;
        var popupX = angular.element(".color-texture-holder").offset().left - angular.element(".root-controller").offset().left;
        var popupY = angular.element(".color-texture-holder").offset().top - angular.element(".root-controller").offset().top;
        var showFlag = false;
        if (x >= popupX && x <= popupX + angular.element(".color-texture-holder").width()) {
            if (y >= popupY && y <= popupY + angular.element(".color-texture-holder").height()) {
                showFlag = true;
            }
        }
        if (showFlag == false) {
            angular.element(".color-texture-holder").css({
                display: "none"
            });
        }
    });
    if (mobileMode) {
        $(".tab-content").css({
            display: "none"
        });
    }
    if (window.innerWidth > 767 && window.innerWidth < 960) {
        angular.element(".info-order").css({
            position: "absolute",
            left: "564px",
            top: "79px",
            maxWidth: "200px",
            minWidth: "200px"
        });
    } else if (window.innerWidth < 767) {
        angular.element(".info-order").css({
            position: "absolute",
            left: "33px",
            top: "471px"
        });
    } else {
        angular.element(".info-order").css({
            position: "absolute",
            left: "603px",
            top: "15px"
        });
    }
    $("#file").customFile();
    var myform = $("#UploadForm");
    $("#UploadForm button.customfile-upload").addClass("btn btn-primary");
    $("#UploadForm button.customfile-upload").text("Upload Photo");
    $(myform).ajaxForm({
        beforeSend: function () {
            if (angular.element(".customfile-filename").attr("title")) {
                var sc = angular.element(".root-controller").scope();
                sc.openPreloader("Uploading Image");
            }
        },
        uploadProgress: function (event, position, total, percentComplete) {},
        complete: function (response) {
            var result = angular.fromJson(response.responseText);
            if (!(result.errorStr == "Error")) {
                var title = angular.element(".customfile-filename").attr("title");
                var indx = title.lastIndexOf(".");
                shapeType = "UploadImage";
                var obj = new Object;
                obj.thumb = result.thumb;
                obj.thumbShow = result.thumb;
                obj.title = title.slice(0, indx);
                uploadImageArr.push(obj);
                var scope = angular.element("#clipartsview").scope();
                scope.updateUploadImage();
                var sc = angular.element(".root-controller").scope();
                sc.hidePopUp("preloader");
            } else {
                var sc = angular.element(".root-controller").scope();
                sc.hidePopUp("preloader");
            }
        }
    });
    $(window).scroll(function () {});
});

function rotateProductIn3D(e) {
    var evt = window.event || e;
    var delta = evt.detail ? evt.detail * -120 : evt.wheelDelta;
    if (delta == 360 || delta == 120) {
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
        var arr = $(".canvas-case-inner div.canvas-container");
        angular.element(arr[nextCounter]).css({
            display: "block"
        });
    } else if (delta == -360 || delta == -120) {
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
        var arr = $(".canvas-case-inner div.canvas-container");
        angular.element(arr[nextCounter]).css({
            display: "block"
        });
    }
    if (evt.preventDefault) {
        evt.preventDefault();
    } else {
        return false;
    }
}

function loadCliparts(obj) {
    if (angular.element(obj).attr("rel") == "clipart") {
        var indx = parseInt(angular.element(obj).parent().index());
        var scope = angular.element("#clipartsview").scope();
        scope.loadClipArtsByCatId(clipartsCat[indx].id);
    } else if (angular.element(obj).attr("rel") == "fonts") {
        var indx = parseInt(angular.element(obj).parent().index());
        selectedFontIndx = indx;
        selectedTextFont = fontsData[selectedFontIndx].fontTtf[0].fontTitle;
        fontTitle = fontsData[selectedFontIndx].title;
        var scope = angular.element("#textsview").scope();
        boldClick = false;
        italicClick = false;
        scope.changeTextFontAndSize("font");
    } else if (angular.element(obj).attr("rel") == "fontSize") {
        var indx = parseInt(angular.element(obj).parent().index());
        selectedFontSizeIndx = indx;
        selectedFontSize = parseInt(fontsSize[selectedFontSizeIndx].fontSize);
        var scope = angular.element("#textsview").scope();
        scope.changeTextFontAndSize("size");
    } else if (angular.element(obj).attr("rel") == "printing") {
        var scope = angular.element("#textsview").scope();
        scope.changePrinting(parseInt(angular.element(obj).parent().index()));
    }
}

function getHex(rgb) {
    rgb = rgb.split(",");
    red = rgb[0].split("(");
    red = red[1];
    green = rgb[1];
    blue = rgb[2].split(")");
    blue = blue[0];
}

function rgbToHex(r, g, b) {
    return (16777216 + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

function hex2Rgb(value) {
    value = value > 16777215 ? 16777215 : value;
    value = value < 0 ? 0 : value;
    var red = value >> 16 & 255;
    var green = value >> 8 & 255;
    var blue = value & 255;
    return [red, green, blue];
}

function loadStrapsFromJquery(event) {
    var str = $(event.target).parent().attr("strap");
    angular.element(".single-check ,.double-check").removeClass("c_on");
    var childs = angular.element(".double-strap div");
    if (str == "single") {
        var removeSelected = angular.element(childs[1]).attr("partT");
        angular.element(".single-check").addClass("c_on");
    }
    if (str == "double") {
        var removeSelected = angular.element(childs[0]).attr("partT");
        angular.element(".double-check").addClass("c_on");
    }
    var scope = angular.element(".root-controller").scope();
    scope.openPreloader();
    scope.addSingleOrDoubleStrap(str, removeSelected);
    event.preventDefault();
    event.stopPropagation();
}

function rotationOnDrag(event) {
    drawNextDiffWidth = $("#wheelEventContainer").width() / 8;
    var scope = angular.element(".root-controller").scope();
    var touch = event.touches[0];
    var nextDragX = touch.pageX;
    if (nextDragX > dragX + drawNextDiffWidth) {
        dragX = nextDragX;
        scope.rotateRightWise();
    } else if (nextDragX < dragX - drawNextDiffWidth) {
        dragX = nextDragX;
        scope.rotateLeftWise();
    }
}

function removeRotationOnDrag(event) {
    document.removeEventListener("touchmove", rotationOnDrag);
    document.removeEventListener("touchend", removeRotationOnDrag);
}

function startInteractionTimer(event) {
    if (clickEventType == "click") {
        var childs = angular.element(".partlayercolorholder li");
        var x = event.pageX - angular.element(".root-controller").offset().left;
        var y = event.pageY - angular.element(".root-controller").offset().top;
        var boundaryX = angular.element(".partlayercolorholder").offset().left - angular.element(".root-controller").offset().left;
        var boundaryY = angular.element(".partlayercolorholder").offset().top - angular.element(".root-controller").offset().top;
        var boundaryFlag = false;
        if (x >= boundaryX && x <= boundaryX + angular.element(".partlayercolorholder").width()) {
            if (y >= boundaryY && y <= boundaryY + angular.element(".partlayercolorholder").height()) {
                boundaryFlag = true;
            }
        }
        if (boundaryFlag == true && leaveColorTextureFlag == true) {
            var showFlag = false;
            var selectedColorObject;
            for (var i = 0; i < childs.length; i++) {
                var popupX = angular.element(childs[i]).offset().left - angular.element(".root-controller").offset().left;
                var popupY = angular.element(childs[i]).offset().top - angular.element(".root-controller").offset().top;
                if (x > popupX && x < popupX + angular.element(childs[i]).width()) {
                    if (y > popupY && y < popupY + angular.element(childs[i]).height()) {
                        showFlag = true;
                        selectedColorObject = childs[i];
                        break;
                    }
                }
            }
            if (showFlag == true) {
                x = x - 2;
                var width = angular.element(".root-controller").width();
                var height = angular.element(".root-controller").height();
                if (x + angular.element(".color-texture-holder").width() > width) {
                    var decrX = x + angular.element(".color-texture-holder").width() - width;
                    x = x - decrX - 2;
                }
                if (y + angular.element(".color-texture-holder").height() > height) {
                    var decrH = y + angular.element(".color-texture-holder").height() - height;
                    y = y - decrH;
                }
                var texture = angular.element(selectedColorObject).attr("texture");
                if (texture) {
                    angular.element(".color-texture-holder").find("div").css({
                        backgroundColor: ""
                    });
                    var colortitle = angular.element(selectedColorObject).attr("colortitle");
                    var texturetitle = angular.element(selectedColorObject).attr("texturetitle");
                    angular.element(".color-texture-holder").find("img").css({
                        display: "block"
                    });
                    angular.element(".color-texture-holder").find("img").attr("src", colorTexturePath + texture);
                    angular.element(".color-texture-holder").find("div").css({
                        backgroundColor: "#" + colorcode
                    });
                    angular.element(".color-texture-holder").find("span").text(texturetitle);
                } else {
                    angular.element(".color-texture-holder").find("img").css({
                        display: "none"
                    });
                    var colorcode = angular.element(selectedColorObject).attr("colorcode");
                    var colortitle = angular.element(selectedColorObject).attr("colortitle");
                    angular.element(".color-texture-holder").find("div").css({
                        backgroundColor: "#" + colorcode
                    });
                    angular.element(".color-texture-holder").find("span").text(colortitle);
                }
                if (preColorSelectedItem) {
                    if (preColorSelectedItem == selectedColorObject) {} else {
                        preColorSelectedItem = selectedColorObject;
                        angular.element(".color-texture-holder").css({
                            display: "block",
                            left: x + "px",
                            top: y + "px"
                        });
                    }
                } else {
                    preColorSelectedItem = selectedColorObject;
                    angular.element(".color-texture-holder").css({
                        display: "block",
                        left: x + "px",
                        top: y + "px"
                    });
                }
            } else {}
        } else {
            preColorSelectedItem = "";
            angular.element(".color-texture-holder").css({
                display: "none"
            });
        }
    }
}
