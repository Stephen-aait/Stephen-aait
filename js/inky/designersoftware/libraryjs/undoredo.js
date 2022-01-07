var CHANGETYPE_ADD= 'add';
var CHANGETYPE_REMOVE = 'remove';
var CHANGETYPE_UPDATE = 'update';
var BACKCOLOR_UPDATE = 'backcolor';
var MAX_UNDO = 50;
var objArr=new Array;
var undoIndex=-1;
var undoIndexArr=new Array();
var redoIndex=-1;
var redoArr=new Array();
for(var i=0;i<8;i++)
{
	previewArr[i]=new Array();
	canvasArr[i]=new Array();
	objArr[i]=new Array();
	undoIndexArr[i]=-1;
}

function setUndoRedo()
{
	objArr=new Array;
	undoIndex=-1;
	undoIndexArr=new Array();
	redoIndex=-1;
	redoArr=new Array();
	for(var i=0;i<8;i++)
	{
		previewArr[i]=new Array();
		canvasArr[i]=new Array();
		objArr[i]=new Array();
		undoIndexArr[i]=-1;
	}
}




 function saveState(object,changeType)
{
	if(changeType=="backcolor")
	{
		var obj=new Object();
	}
	else
	{
		//canvas=canvasViewArray[currentView].canvas;
		if(object.get('shapeType')=="grp")
		{
			var objects=canvasViewArray[0].canvas.getObjects();
			var indx=objects.indexOf(object);
			var obj=new Object();
			undoIndexArr[0]++;
			obj.changeType=changeType;
			obj.ref=object;
			obj.index=indx;
			
			var objects2=canvasViewArray[0].canvas.getObjects();
			var indx2=objects2.indexOf(object);
			var obj2=new Object();
			undoIndexArr[4]++;
			obj2.changeType=changeType;
			obj2.ref=object;
			obj2.index=indx2;
		}
		else
		{
			var objects=canvas.getObjects();
			var indx=objects.indexOf(object);
			
			var obj=new Object();
			undoIndexArr[currentView]++;
			obj.changeType=changeType;
			obj.ref=object;
			obj.index=indx;
			
		}
		
		var rotationObj=object.get("oCoords").tl;
		if(object.get('shapeType')=="Text")
		{
			//console.log(rotationObj)
			obj.originalTextFontWidth=object.get('originalTextFontWidth');
			obj.originalTextFontHeight=object.get('originalTextFontHeight');
			obj.fontSize=object.get('fontSize');
			obj.fontFamily=object.get('fontFamily');
			obj.text=object.get('text');
			obj.textColorPrice=object.get('colorPrice');
			obj.textDecoration=object.get("textDecoration");
			obj.boldClick=object.get('boldClick');
			obj.italicClick=object.get('italicClick');
			obj.fontTitle=object.get('fontTitle');
			obj.colorTitle=object.get('colorTitle');
			obj.selectedFontIndx=object.get('selectedFontIndx');
			obj.selectedFontSize=object.get('selectedFontSize');
			obj.artColorSelectedIndx=object.get('artColorSelectedIndx');
			obj.textAlign=object.get('textAlign');
		}
		obj.colorable=object.get('colorable');
		obj.hexColorCode=object.get('hexColorCode');
		obj.artColorSelectedIndx=object.get('artColorSelectedIndx');
		obj.shapeType=object.get('shapeType');
		obj.price=object.get('price');
		obj.clipColorPrice=object.get('colorPrice');
		obj.width=object.getWidth();
		obj.colorTitle=object.get('colorTitle');
		obj.height=object.getHeight();
		obj.left=rotationObj.x
		obj.top=rotationObj.y;
		obj.angle=object.getAngle();
		obj.scaleX=object.scaleX;
		obj.scaleY=object.scaleY;
		obj.groupTitle=object.get('groupTitle');
		obj.partName=object.get('partTitle');
		//console.log(object.getWidth())
	}
	//console.log(undoIndexArr[currentView])
	if(object.get('shapeType')=="grp")
	{
		objArr[0].splice(undoIndexArr[currentView],0,obj);
		objArr[4].splice(undoIndexArr[currentView],0,obj);


	}
	else
	objArr[currentView].splice(undoIndexArr[currentView],0,obj);
	//console.log(objArr)
}
 function undo()
{
	//alert("undo")
	//alert(undoIndexArr[currentView])
	if(undoIndexArr[currentView]>-1)
	{
		//console.log(objArr)
		//console.log(undoIndexArr[currentView])
		
		var undoobj=objArr[currentView][undoIndexArr[currentView]];
		//console.log(undoobj)
		undoIndexArr[currentView]--;
		//alert(undoobj.changeType)
		if(undoobj.changeType==CHANGETYPE_UPDATE)
		{
			//alert("anadi")
			canvas=canvasViewArray[currentView].canvas;
			canvas.discardActiveObject();
			for(var i=undoIndexArr[currentView];i>=0;i--)
			{
				
				if(objArr[currentView][i].shapeType==undoobj.shapeType)
				{
					var undoobj2=objArr[currentView][i];
					break;
				}
			}
			
		//	alert("update");
			//console.log(undoobj2.changeType)
			var upObj=canvas.item(undoobj2.index)
			if( upObj && upObj.get("shapeType")=="Text")
			{
				//console.log(rotationObj)
				var newFontSize=(fabricFontSize*undoobj2.width)/undoobj2.originalTextFontWidth;
				//console.log(newFontSize)
				canvas.remove(upObj);
				canvas.renderAll();
				//console.log(undoobj2.text)
				textColorSelectedIndx=undoobj2.artColorSelectedIndx;
				textColor=undoobj2.hexColorCode;
				textColorPrice=undoobj2.textColorPrice;
				textColorTitle:undoobj2.colorTitle;
				textAlign=undoobj2.textAlign;
				var img =new fabric.Text(undoobj2.text,{name:"Text",
						fontFamily:undoobj2.fontFamily,
						shapeType:"Text",
						text:undoobj2.text,
						fontSize:newFontSize,
						colorPrice:textColorPrice,
						colorTitle:undoobj2.colorTitle,
						fontTitle:undoobj2.fontTitle,
						originalTextFontWidth:undoobj2.originalTextFontWidth,
						originalTextFontHeight:undoobj2.originalTextFontHeight,
						textAlign:textAlign,
						hexColorCode:undoobj2.hexColorCode,
						fill:"#"+undoobj2.hexColorCode,
						boldClick:undoobj2.boldClick,
						italicClick:undoobj2.italicClick,
						textDecoration:undoobj2.textDecoration,
						selectedFontSize:undoobj2.selectedFontSize,
						selectedFontIndx:undoobj2.selectedFontIndx,
						textAlign:undoobj2.textAlign,
						artColorSelectedIndx:undoobj2.artColorSelectedIndx
						
						})
						canvas.add(img);
						canvas.renderAll();
						//console.log(img.width)
						img.set({left:undoobj2.left+undoobj2.width/2,
								top:undoobj2.top+undoobj2.height/2,
								originX:"center",
								originY:"center",
					angle:undoobj2.angle});
					
				img.setCoords();
				canvas.calcOffset();
				canvas.renderAll();
				//upObj.set({fontSize:30});
				canvas.renderAll();
				undoobj2.ref=img;
				var scope=angular.element(".root-controller").scope();
				scope.calculateTotalPrice();
				//saveState(img,undoobj2.changeType);
			}
			else if(upObj)
			{
				scaleRatioFlag=false;
				scaleRatioWidth=undoobj2.width;
				scaleRatioHeight=undoobj2.height;
				//console.log(undoobj2.colorable)
				if(undoobj2.colorable==1)
				{
						artColorSelectedIndx=undoobj2.artColorSelectedIndx;
						defaultClipColor=undoobj2.hexColorCode;
						clipColorPrice=undoobj2.clipColorPrice;
						clipColorTitle=undoobj2.colorTitle;
						global_SelectedItem=upObj;
						var arr=hex2Rgb(("0x"+undoobj2.hexColorCode));
						colorPickerValue='rgb('+arr[0]+','+arr[1]+','+arr[2]+')';
						global_SelectedItem.set({hexColorCode:undoobj2.hexColorCode,
						colorPrice:undoobj2.clipColorPrice,
						colorTitle:undoobj2.colorTitle,	
						artColorSelectedIndx:undoobj2.artColorSelectedIndx});
						global_SelectedItem.fill=colorPickerValue;
						colorPickerValue =colorPickerValue;
						global_SelectedItem.filters[1] = new fabric.Image.filters.Invert();
						global_SelectedItem.applyFilters(canvas.renderAll.bind(canvas));
				}
				upObj.set({left:undoobj2.left,
					top:undoobj2.top,
					
					width:undoobj2.width,
					height:undoobj2.height,
					angle:undoobj2.angle});
					
				upObj.setCoords();
				canvas.calcOffset();
				canvas.renderAll();
			}
			
			
		}
		else if(undoobj.changeType==CHANGETYPE_ADD)
		{
			//canvas=canvasViewArray[currentView].canvas;
			var deteleobj=undoobj.ref;
			if(deteleobj.get("groupTitle")=="Pocket" || deteleobj.get("groupTitle")=="Handle")
			{
				//console.log(deteleobj.get("partName"))
				var scope=angular.element(".root-controller").scope();
				scope.removeSelectedGroup(deteleobj.get("partName"))
			}
			else
			{
				if(deteleobj.get("shapeType")=="Option")
				{
					var scope=angular.element(".root-controller").scope();
					scope.removeOptionSizes(deteleobj.get("optionId"))
				}
				canvas.remove(deteleobj);
				canvas.renderAll();
			}
			
		}
		
	}
}
 function redo()
{
	
	if(undoIndex<objArr.length-1)
	{
		undoIndex++;
		var obj=objArr[undoIndex];
		if(obj.changeType==CHANGETYPE_ADD)
		{
			//drawArea.addElementAt(obj.ref,obj.index);
			canvas.add(obj.ref);
			canvas.renderAll();
		}
		else if(obj.changeType==CHANGETYPE_UPDATE)
		{
			var obj2=objArr[undoIndex];
			scaleRatioFlag=false;
			scaleRatioWidth=obj2.width;
			scaleRationHeight=obj2.height;
			var upObj=canvas.item(obj2.index)
			upObj.set({left:obj2.left,
				top:obj2.top,
				width:obj2.width,
				height:obj2.height,
				angle:obj2.angle});
			canvas.setActiveObject(upObj)
			upObj.setCoords();
			canvas.calcOffset();
			canvas.renderAll();
			
		}
	}
		
}
