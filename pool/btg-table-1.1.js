/**
 * BTG Pool Table Diagramming Tool
 *
 * This javascript library is for diagramming pool table layouts
 * and easily sharing them on other sites and forums. This code
 * cannot be copied, modified or otherwise without written consent.
 *
 * @name   	   BTG Table Diagramming Tool
 * @author     Monte Ohrt <monte@ohrt.com>
 * @copyright  2013 Monte Ohrt
 * @license    Private License 1.0
 * @version    Release: 1.1
 * @link       http://www.billiardsthegame.com/
 */

function BTGTable() {
  this.stage = null;
  this.tableOutsideLayer = null;
  this.tableSurfaceLayer = null;
  this.tableOutsideGroup = null;
  this.tableSurfaceGroup = null;
  this.gridGroup = null;
  this.gridHidden = false;
  this.lineMode = false;
  this.line = null;
  this.lineModeCircle = false;
  this.lineStartPoint = null;
  this.lineLayer = null;
  this.ballColors = {
    "1": "gold",
    "2": "blue",
    "3": "red",
    "4": "purple",
    "5": "#ff6600",
    "6": "green",
    "7": "maroon",
    "8": "black",
    "9": "gold",
    "10": "blue",
    "11": "red",
    "12": "purple",
    "13": "#ff6600",
    "14": "green",
    "15": "maroon",
    "16": "white",
    "17": "lightgrey",
    "18": "lightgrey"
  };
  this.ballsLayer = null;
  this.ballsArray = [];
  this.images = [];
  this.resetPositions = {
    "clear": {
      0: {
        x: 20,
        y: 490
      },
      1: {
        x: 40,
        y: 490
      },
      2: {
        x: 60,
        y: 490
      },
      3: {
        x: 80,
        y: 490
      },
      4: {
        x: 100,
        y: 490
      },
      5: {
        x: 120,
        y: 490
      },
      6: {
        x: 140,
        y: 490
      },
      7: {
        x: 160,
        y: 490
      },
      8: {
        x: 180,
        y: 490
      },
      9: {
        x: 200,
        y: 490
      },
      10: {
        x: 220,
        y: 490
      },
      11: {
        x: 240,
        y: 490
      },
      12: {
        x: 260,
        y: 490
      },
      13: {
        x: 280,
        y: 490
      },
      14: {
        x: 300,
        y: 490
      },
      15: {
        x: 320,
        y: 490
      },
      16: {
        x: 340,
        y: 490
      },
      17: {
        x: 360,
        y: 490
      }
    },
    "8-ball": {
      0: {
        x: 220,
        y: 220
      },
      1: {
        x: 149,
        y: 180
      },
      2: {
        x: 185,
        y: 200
      },
      3: {
        x: 167,
        y: 230
      },
      4: {
        x: 185,
        y: 240
      },
      5: {
        x: 149,
        y: 220
      },
      6: {
        x: 167,
        y: 190
      },
      7: {
        x: 185,
        y: 220
      },
      8: {
        x: 149,
        y: 200
      },
      9: {
        x: 203,
        y: 210
      },
      10: {
        x: 167,
        y: 210
      },
      11: {
        x: 149,
        y: 260
      },
      12: {
        x: 149,
        y: 240
      },
      13: {
        x: 203,
        y: 230
      },
      14: {
        x: 167,
        y: 250
      },
      15: {
        x: 660,
        y: 220
      },
      16: {
        x: 340,
        y: 490
      },
      17: {
        x: 360,
        y: 490
      }
    },
    "9-ball": {
      0: {
        x: 220,
        y: 220
      },
      1: {
        x: 149,
        y: 220
      },
      2: {
        x: 185,
        y: 200
      },
      3: {
        x: 203,
        y: 230
      },
      4: {
        x: 185,
        y: 240
      },
      5: {
        x: 167,
        y: 230
      },
      6: {
        x: 203,
        y: 210
      },
      7: {
        x: 167,
        y: 210
      },
      8: {
        x: 185,
        y: 220
      },
      9: {
        x: 200,
        y: 490
      },
      10: {
        x: 220,
        y: 490
      },
      11: {
        x: 240,
        y: 490
      },
      12: {
        x: 260,
        y: 490
      },
      13: {
        x: 280,
        y: 490
      },
      14: {
        x: 300,
        y: 490
      },
      15: {
        x: 660,
        y: 220
      },
      16: {
        x: 340,
        y: 490
      },
      17: {
        x: 360,
        y: 490
      }
    },
    "10-ball": {
      0: {
        x: 220,
        y: 220
      },
      1: {
        x: 167,
        y: 210
      },
      2: {
        x: 203,
        y: 230
      },
      3: {
        x: 185,
        y: 200
      },
      4: {
        x: 167,
        y: 230
      },
      5: {
        x: 203,
        y: 210
      },
      6: {
        x: 185,
        y: 240
      },
      7: {
        x: 167,
        y: 190
      },
      8: {
        x: 167,
        y: 250
      },
      9: {
        x: 185,
        y: 220
      },
      10: {
        x: 220,
        y: 490
      },
      11: {
        x: 240,
        y: 490
      },
      12: {
        x: 260,
        y: 490
      },
      13: {
        x: 280,
        y: 490
      },
      14: {
        x: 300,
        y: 490
      },
      15: {
        x: 660,
        y: 220
      },
      16: {
        x: 340,
        y: 490
      },
      17: {
        x: 360,
        y: 490
      }
    }
  };
}

BTGTable.prototype.setupTable = function(container, width, height) {
  var _this = this;
  this.stage = new Kinetic.Stage({
    container: container,
    width: width,
    height: height
  });
  this.tableOutsideLayer = new Kinetic.Layer({});
  this.tableSurfaceLayer = new Kinetic.Layer({
    x: 30,
    y: 30
  });
  this.tableOutsideGroup = new Kinetic.Group({});
  this.tableSurfaceGroup = new Kinetic.Group({
    listening: false
  });

  this.tableSurfaceGroup.on('mousemove', function(moveObj) {
    console.log('mousemove');
    _this.lineModeCircle.setAbsolutePosition(moveObj.x, moveObj.y);
    _this.line.setPoints([_this.lineStartPoint.x - 30, _this.lineStartPoint.y - 30, moveObj.x - 30, moveObj.y - 30]);
    _this.tableSurfaceLayer.draw();
  });

  this.gridGroup = new Kinetic.Group({});

  // outside laminate edges
  var tableOutsideRect = new Kinetic.Rect({
    width: 940,
    height: 500,
    fillPatternImage: this.images.grain
  });
  this.tableOutsideGroup.add(tableOutsideRect);

  this.tableOutsideGroup.add(this.getGlareLine(3, 3, 937, 3, 3));
  this.tableOutsideGroup.add(this.getGlareLine(3, 497, 937, 497, 3));
  this.tableOutsideGroup.add(this.getGlareLine(3, 3, 3, 497, 3));
  this.tableOutsideGroup.add(this.getGlareLine(937, 3, 937, 497, 3));

  var utilArea = new Kinetic.Rect({
    x: 0,
    y: 500,
    width: 940,
    height: 40,
    fill: "#292421"
  });
  this.tableOutsideGroup.add(utilArea);

  this.tableOutsideLayer.add(this.tableOutsideGroup);

  // playing surface
  var tableSurfaceRect = new Kinetic.Rect({
    width: 880,
    height: 440,
    fillRadialGradientStartPoint: {
      x: 440,
      y: 220
    },
    fillRadialGradientStartRadius: 0,
    fillRadialGradientEndPoint: {
      x: 440,
      y: 220
    },
    fillRadialGradientEndRadius: 440,
    fillRadialGradientColorStops: [0, '#00dd00', 1, '#008800']
  });
  this.tableSurfaceGroup.add(tableSurfaceRect);

  // text on table surface
  var tableSurfaceText = new Kinetic.Text({
    x: 260,
    y: 180,
    text: 'www.billiardsthegame.com',
    fontSize: 32,
    fontFamily: 'Helvetica',
    fontStyle: 'normal',
    fill: '#666',
    align: 'center',
    fontStyle: 'italic',
    opacity: 0.2,
    shadowColor: 'black',
    shadowBlur: 10,
    shadowOffset: {
      x: 5,
      y: 5
    },
    shadowOpacity: 0.4
  });
  this.tableSurfaceGroup.add(tableSurfaceText);

  this.gridGroup.add(this.getGridLine(0, 110, 880, 110));
  this.gridGroup.add(this.getGridLine(0, 220, 880, 220));
  this.gridGroup.add(this.getGridLine(0, 330, 880, 330));
  this.gridGroup.add(this.getGridLine(110, 0, 110, 440));
  this.gridGroup.add(this.getGridLine(220, 0, 220, 440));
  this.gridGroup.add(this.getGridLine(330, 0, 330, 440));
  this.gridGroup.add(this.getGridLine(440, 0, 440, 440));
  this.gridGroup.add(this.getGridLine(550, 0, 550, 440));
  this.gridGroup.add(this.getGridLine(660, 0, 660, 440));
  this.gridGroup.add(this.getGridLine(770, 0, 770, 440));
  this.tableSurfaceGroup.add(this.gridGroup);

  this.tableSurfaceLayer.add(this.tableSurfaceGroup);
  
  // add diamonds on rails
  this.tableSurfaceLayer.add(this.getDiamond(-14,110));
  this.tableSurfaceLayer.add(this.getDiamond(-14,220));
  this.tableSurfaceLayer.add(this.getDiamond(-14,330));
  this.tableSurfaceLayer.add(this.getDiamond(894,110));
  this.tableSurfaceLayer.add(this.getDiamond(894,220));
  this.tableSurfaceLayer.add(this.getDiamond(894,330));
  this.tableSurfaceLayer.add(this.getDiamond(110,-14));
  this.tableSurfaceLayer.add(this.getDiamond(220,-14));
  this.tableSurfaceLayer.add(this.getDiamond(330,-14));
  this.tableSurfaceLayer.add(this.getDiamond(110,454));
  this.tableSurfaceLayer.add(this.getDiamond(220,454));
  this.tableSurfaceLayer.add(this.getDiamond(330,454));
  this.tableSurfaceLayer.add(this.getDiamond(550,-14));
  this.tableSurfaceLayer.add(this.getDiamond(660,-14));
  this.tableSurfaceLayer.add(this.getDiamond(770,-14));
  this.tableSurfaceLayer.add(this.getDiamond(550,454));
  this.tableSurfaceLayer.add(this.getDiamond(660,454));
  this.tableSurfaceLayer.add(this.getDiamond(770,454));
  

  this.tableSurfaceGroup.add(this.getPocket(5, 5, 20));
  this.tableSurfaceGroup.add(this.getPocket(875, 5, 20));
  this.tableSurfaceGroup.add(this.getPocket(5, 435, 20));
  this.tableSurfaceGroup.add(this.getPocket(875, 435, 20));
  this.tableSurfaceGroup.add(this.getPocket(440, -5, 20));
  this.tableSurfaceGroup.add(this.getPocket(440, 445, 20));

  this.tableSurfaceGroup.add(this.getRail({
    x: 25,
    y: 0
  }, {
    x: 420,
    y: 0
  }, {
    x: 415,
    y: 10
  }, {
    x: 35,
    y: 10
  }, {
    x: 0,
    y: 4
  }));
  this.tableSurfaceGroup.add(this.getRail({
    x: 460,
    y: 0
  }, {
    x: 855,
    y: 0
  }, {
    x: 845,
    y: 10
  }, {
    x: 465,
    y: 10
  }, {
    x: 0,
    y: 4
  }));
  this.tableSurfaceGroup.add(this.getRail({
    x: 25,
    y: 440
  }, {
    x: 420,
    y: 440
  }, {
    x: 415,
    y: 430
  }, {
    x: 35,
    y: 430
  }, {
    x: 0,
    y: -4
  }));
  this.tableSurfaceGroup.add(this.getRail({
    x: 460,
    y: 440
  }, {
    x: 855,
    y: 440
  }, {
    x: 845,
    y: 430
  }, {
    x: 465,
    y: 430
  }, {
    x: 0,
    y: -4
  }));
  this.tableSurfaceGroup.add(this.getRail({
    x: 0,
    y: 25
  }, {
    x: 10,
    y: 35
  }, {
    x: 10,
    y: 405
  }, {
    x: 0,
    y: 415
  }, {
    x: 4,
    y: 0
  }));
  this.tableSurfaceGroup.add(this.getRail({
    x: 880,
    y: 25
  }, {
    x: 870,
    y: 35
  }, {
    x: 870,
    y: 405
  }, {
    x: 880,
    y: 415
  }, {
    x: -4,
    y: 0
  }));

  this.stage.add(this.tableOutsideLayer);
  this.stage.add(this.tableSurfaceLayer);

  this.ballsLayer = new Kinetic.Layer({
    x: 30,
    y: 30
  });
  for (var n = 0; n < 18; n++) {
    var i = n;
    var ball;
    ball = this.getBall(i + 1, this.resetPositions["8-ball"][i].x, this.resetPositions["8-ball"][i].y);
    this.ballsLayer.add(ball);
    this.ballsArray.push(ball);
  }
  this.stage.add(this.ballsLayer);

};

BTGTable.prototype.getGridLine = function(startX, startY, endX, endY) {
  var line = new Kinetic.Line({
    points: [startX, startY, endX, endY],
    stroke: "#444",
    strokeWidth: 0.5,
    dashArray: [1, 1]
  });
  return line;
};

BTGTable.prototype.getDiamond = function(x, y) {
  var circle = new Kinetic.Circle({
    radius: 2,
    x: x,
    y: y,
    fill: 'white'
  });
  return circle;
};

BTGTable.prototype.getPocket = function(x, y, r) {
  var pocket = new Kinetic.Circle({
    x: x,
    y: y,
    radius: r,
    fill: 'black'
  });
  return pocket;
}

BTGTable.prototype.getGlareLine = function(x1, y1, x2, y2, width) {
  var glareLine = new Kinetic.Line({
    points: [x1, y1, x2, y2],
    stroke: "white",
    strokeWidth: width,
    lineCap: "round",
    lineJoin: "round",
    opacity: 0.4
  });
  return glareLine;
}

BTGTable.prototype.getRail = function(p1, p2, p3, p4, offset) {
  var rail = new Kinetic.Polygon({
    points: [p1.x, p1.y, p2.x, p2.y, p3.x, p3.y, p4.x, p4.y],
    fill: "#009900",
    shadowColor: 'black',
    shadowBlur: 10,
    shadowOffset: [offset.x, offset.y],
    opacity: 0.5
  });
  return rail;
};

BTGTable.prototype.startLineMode = function(x, y) {
  if (this.lineMode) {
    this.stopLineMode();
  }
  var _this = this;
  document.body.style.cursor = "crosshair";
  //this.lineLayer = new Kinetic.Layer();
  this.lineMode = true;
  if (!this.lineModeCircle) {
    this.lineModeCircle = new Kinetic.Circle({
      x: x,
      y: y,
      radius: 10,
      stroke: 'black',
      strokeWidth: 1,
      visible: true
    });
  } else {
    this.lineModeCircle.setVisible(true);
  }
  this.tableSurfaceGroup.add(this.lineModeCircle);
  this.lineStartPoint = {
    x: x,
    y: y
  };
  this.line = new Kinetic.Line({
    points: [50, 50, 100, 100],
    stroke: 'black',
    strokeWidth: 2
  });
  this.tableSurfaceGroup.add(this.line);
  //this.stage.add(this.lineLayer);
  this.tableSurfaceGroup.setListening(true);
}

BTGTable.prototype.stopLineMode = function() {
  document.body.style.cursor = "default";
  this.lineModeCircle.setVisible(false);
  this.line.setVisible(false);
  this.tableSurfaceGroup.setListening(false);
}

BTGTable.prototype.resetBallPositions = function(positions) {
	for (var i = 0; i < this.ballsArray.length; i++) {
	    this.ballsArray[i].transitionTo({x: positions[i].x, y: positions[i].y, duration: 0.5});
	}
}

BTGTable.prototype.getBallPositions = function() {
  var positions = Array();
  for (var i = 0; i < this.ballsArray.length; i++) {
      positions.push({x: this.ballsArray[i].attrs.x, y: this.ballsArray[i].attrs.y});
	}
  return positions;
}

BTGTable.prototype.getBall = function(number, x, y) {
  var _this = this;
  var group = new Kinetic.Group({
    x: x,
    y: y,
    draggable: true,
    listening: true
  });
  var _group = group;
  /*
  group.on("dblclick", function(clickObj) {
    _this.startLineMode(clickObj.x, clickObj.y);
  });
  */
  var circle = new Kinetic.Circle({
    radius: 10,
    fill: this.ballColors[number],
    //shadowColor: 'black',
    //shadowBlur: 10,
    //shadowOffset: [2, 2],
    strokeColor: 'black',
    strokeWidth: number > 16 ? 1 : 0,
    dashArray: [2,2],
    opacity: 1.0
  });
  circle.on('mouseover', function() {
    document.body.style.cursor = 'pointer';
  });
  circle.on('mouseout', function() {
    document.body.style.cursor = 'default';
  });
  group.add(circle);
  if (number <= 15) {
    // white circle around number on pool ball
    var circle2 = new Kinetic.Circle({
      radius: 5,
      fill: "white"
    });
    group.add(circle2);
    // text on pool ball
    var text = new Kinetic.Text({
      x: number < 10 ? -2 : -3.8,
      y: number < 10 ? -3 : -2.5,
      text: number,
      align: "center",
      fontSize: number < 10 ? 8 : 7,
      fontFamily: "Helvetica",
      fontStyle: "Bold",
      fill: "black"
    });
    group.add(text);
    if (number > 8) {
      // top white part of stripe ball
      var circleClipTop = new Kinetic.Shape({
        drawFunc: function() {
          var context = this.getContext();
          var radius = 10;
          var startAngle = 1.25 * Math.PI;
          var endAngle = 1.75 * Math.PI;
          var counterClockwise = false;
          context.beginPath();
          context.arc(0, 0, radius, startAngle, endAngle, counterClockwise);
          context.closePath();
          context.fillStyle ='white';
          context.fill();
        },
        fill: "white"
      });
      // bottom white part of stripe ball
      var circleClipBottom = new Kinetic.Shape({
        drawFunc: function() {
          var context = this.getContext();
          var radius = 10;
          var startAngle = 0.25 * Math.PI;
          var endAngle = 0.75 * Math.PI;
          var counterClockwise = false;
          context.beginPath();
          context.arc(0, 0, radius, startAngle, endAngle, counterClockwise);
          context.closePath();
          context.fillStyle = 'white';
          context.fill();
        },
        fill: "white"
      });
      group.add(circleClipTop);
      group.add(circleClipBottom);
    }
  } else if(number == 16) {
    // red dot on cue ball
    var circle2 = new Kinetic.Circle({
      radius: 2,
      fill: "red"
    });
    group.add(circle2);
  } else {
    // opacity of ghost balls
    group.opacity = 0.5;
  }
  // light glare circle
  var glare = new Kinetic.Ellipse({
    y: -6,
    fill: "white",
    opacity: 0.4
  });
  glare.setRadius({
    x: 6,
    y: 4
  });
  group.add(glare);
  return group;
};
