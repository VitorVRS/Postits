var Postit = function(id) {

  /**
   * Postit id from database
   */
  this.id = id || '_' + Math.random().toString(36).substr(2, 9);

  /**
   * Title value
   */
  this.title = '';

  /**
   * Content value
   */  
  this.content = '';

  /**
   * Postit color
   */ 
  this.color = '';

  /**
   * Variable that handle ajax flood
   */   
  this.ajaxSave = null;

  /**
   * Available colors
   */ 
  this.colors = [
    "rgb(255, 255, 165)",
    "rgb(243, 186, 225)",
    "rgb(199, 240, 138)",
    "rgb(202, 228, 235)",
    "rgb(226, 202, 235)"
  ];

  /**
   * jQuery object from .postit element
   */
  this.$wrapper = {};

  /**
   * jQuery object from .postit-menu element
   */
  this.$menu    = {};

  /**
   * jQuery object from .postit-title element
   */
  this.$title   = {};

  /**
   * jQuery object from .postit-content element
   */
  this.$content = {};

  /**
   * Call create method to initialize postit
   */
  this.create();

  /**
   * Call method to initializae events from postit
   */ 
  this.registerEvents();

};


/**
 * Create postit structure on DOM
 */
Postit.prototype.create = function() {

  this.$wrapper = $("<div>", {"class" : "postit"})

  this.$menu    = $("<ul>" , {"class" : "postit-menu"})
  this.$menu.append($("<li>", {"class": "postit-menu-item delete", "title": "I don't need this anymore!"}).append($("<i>", {"class": "fa fa-trash-o"})))
            .append($("<li>", {"class": "postit-menu-item color" , "title": "Change color!!1"}).append($("<i>", {"class": "fa fa-tasks"  })))

  if ($.pep) {
    this.$menu.append($("<li>", {"class": "postit-menu-item drag"  , "title": "MOVE! MOVE!"}).append($("<i>", {"class": "fa fa-arrows"  })))
  }

  this.$title   = $("<div>", {"class" : "postit-title", "contentEditable": true})
  this.$title.html(this.title)

  this.$content = $("<div>", {"class" : "postit-content", "contentEditable": true})
  this.$content.html(this.content);

  this.$wrapper.append(this.$menu).append(this.$title).append(this.$content);

  this.$wrapper.data("postit", this);
}

/**
 * Start event listeners to postit
 *  - Delete, Change Color, Automatic Save, Drag & Drop
 */
Postit.prototype.registerEvents = function() {
  var me = this;

  this.$wrapper.on("click", ".delete", function() {
    if (confirm("Are you sure?"))  {
      me.delete();
    }
  }).on("click", ".color", function() {
    me.changeColor();
    me.$wrapper.trigger("change")
  }).on("DOMSubtreeModified DOMNodeInserted DOMNodeRemoved", function(){
    me.$wrapper.trigger("change")
  }).on("change", function() {
    me.updateFields();
    me.save();
  })

  if ($.pep) {

    this.$wrapper.pep({
      shouldEase: false,
      constrainTo: '#container',
      removeMargins: false,
      elementsWithInteraction: '.postit-title, .postit-content'
    });

    this.$wrapper.on("mousedown", function() {
      me.drag.apply(me, arguments)
    })
    .on("mouseup", function(e) {
      me.drop.apply(me, arguments)
    });

  }

}

/**
 * Select next color available to postit
 */
Postit.prototype.changeColor = function() {
  var act, next,
      actColor = this.$wrapper.css("backgroundColor");

  for (color in this.colors) {
    if (actColor == this.colors[color]) {
      act = color;
    }
  }
  
  if (act == this.colors.length-1) {
    act = -1;
  }

  next = this.colors[+act+1];
  this.setColor(next);
}

/**
 * Delete postit from DOM, Memory and Database
 */
Postit.prototype.delete = function() {
  localStorage.removeItem("Postit"+this.id)

  var self = this;

  $.ajax({
    url: "services.php",
    type: "post",
    dataType: "json",
    data: {"method" : "deletePostit", "id" : this.id, "userData": Postit.getProfile()},
    success: function(data) {

      if (data.status) {
        self.$wrapper.remove();
      } else {
        alert("Postit not removed.")
      }

    },
    error: function() {
      console.error(arguments)
    },
    complete: function(data) {
    }
  })
}

/**
 * Insert postit at specific element (generally #container)
 */
Postit.prototype.insertTo = function($destiny) {
  $destiny.append(this.$wrapper);
}

/**
 * Save postit data on Memory and Database
 */
Postit.prototype.save = function() {
  localStorage["Postit"+this.id] = this.toJSON();

  if (this.ajaxSave != null) {
    this.ajaxSave.abort();
  }

  this.ajaxSave = $.ajax({
    url: "services.php",
    type: "post",
    dataType: "json",
    data: {"method" : "savePostit", "oPostit" : this.toObject(), "userData": Postit.getProfile()},
    success: function(data) {

    },
    error: function() {
      console.error(arguments)
    },
    complete: function(data) {
    }
  })
}

/**
 * Content setter
 */
Postit.prototype.setContent = function(content) {
  this.$content.html(content)
}

/**
 * Title setter
 */
Postit.prototype.setTitle = function(title) {
  this.$title.html(title)
}

/**
 * Color setter
 */
Postit.prototype.setColor = function(color) {
  this.$wrapper.css("backgroundColor", color)
}

//@TODO
Postit.prototype.load = function() {

}

/**
 * Convert Postit instance to standar object
 */
Postit.prototype.toObject = function() {
  var oData = {
    id: this.id,
    title: this.title,
    content: this.content,
    color: this.color
  }

  return oData;
}

/**
 * Convert Postit instance to JSON
 */
Postit.prototype.toJSON = function() {
  return JSON.stringify(this.toObject());
}

/**
 * Sync attributes and DOM values
 */
Postit.prototype.updateFields = function() {
  this.title = this.$title.html();
  this.content = this.$content.html();
  this.color   = this.$wrapper.css("backgroundColor");
}

/**
 * Drag handler
 */
Postit.prototype.drag = function(e) {

  if ( $(e.target).is(".drag") || $(e.target).parent().is(".drag")) {

    this.toTop();

    return;
  }

  this.$wrapper.trigger("mouseup");
 
}

/**
 * Drop handler
 */
Postit.prototype.drop = function(e) {
    if (e.isTrigger) {
      return;
    }
}

Postit.prototype.toTop = function() {

  var newZIndex = (new Date()).getTime() - 1397998000000;                                        

  this.$wrapper.css({zIndex: newZIndex})
}

/**
 * Set profile data (Google API) to Postit
 */
Postit.setProfile = function(oProfile) {
  this.oProfile = oProfile;
}

/**
 * Profile data getter
 */
Postit.getProfile = function() {
  return this.oProfile;
}

/**
 * Retrieve all postits from database based on profile, and append to DOM.
 */
Postit.loadAll = function ($container) {

  var oProfile = Postit.getProfile();

  var aPostit = localStorage.getItem("aPostit")

  $.ajax({
    url: "services.php",
    type: "post",
    dataType: "json",
    data: {"method" : "loadAll", "userData" : oProfile},
    success: function(data) {

      if (data && data.postits) {
        $.each(data.postits, function() {
            var oPostit = new Postit(this.id);
            oPostit.setTitle(this.title);
            oPostit.setContent(this.content);
            oPostit.setColor(this.color);
            oPostit.insertTo($container);
        })
      }

    },
    error: function() {
      console.error(arguments)
    },
    complete: function(data) {
      //alert(data.responseJSON.message)
    }
  })

  return;
}
