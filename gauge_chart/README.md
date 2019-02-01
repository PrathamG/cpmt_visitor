# Kunstmaan gauge

## What is it?
The kumaGauge is a jQuery plugin to create easy gauges with raphaël.js. 
You can customise the gauges with a number of options.

Dependencies:
    * [jQuery](http://jquery.com)
    * [Raphaël.js](http://raphaeljs.com/)

## How does it work?
### Initialise the plugin 
```
$(element).kumaGauge({
    value : 12
})
```
This creates the gauge with the config you supply

### Update the plugin
```
$(element).kumaGauge('update', {
    value : 12,
    min : 10,
    max : '200'
})
```
This updates the gauge with the values you supply. The only values that can be updated are: value, min and max

## Available config.

```
{
    radius : 80, // Integer: The radius of the arc
    paddingX : 40, // Integer: The padding on the top and bottom of the gauge
    paddingY : 40,  // Integer: The padding on the left and right of the gauge
    gaugeWidth : 30, // Integer: The width of the gauge itseld

    fill : '0-#1cb42f:0-#fdbe37:50-#fa4133:100', // String: The fill of the gauge, this can be a solid color or a gradient
    gaugeBackground : '#f4f4f4', // String: The fill of the gauge background, this can be a solid color or a gradient
    background : '#fff', // String: The fill of the canvas, this can be a solid color or a gradient

    showNeedle : true, // Boolean: Show or hide the needle, if true the value label shows half of the range, if false the value label shows the value

    animationSpeed : 500, // Integer: The speed of the animation in miliseconds

    min : 0, // Float: The minimum value of the gauge
    max : 100, // Float: The maximum value of the gauge
    value : 80, // Float: The actual value of the gauge

    // The label that indicates the value
    valueLabel : {
        display : true, // Boolean: show or hide this label
        fontFamily : 'Arial', // String: The font family of this label
        fontColor : '#000', // String: The font color of this label
        fontSize : 20, // Integer of String: The font size of this label (without px)
        fontWeight : 'normal' // String: The font weight of this label
    },
    title : {
        display : true, // Boolean: show or hide this label
        value : '', // String the value of the title
        fontFamily : 'Arial', // String: The font family of this label
        fontColor : '#000', // String: The font color of this label
        fontSize : 20, // Integer of String: The font size of this label (without px)
        fontWeight : 'normal' // String: The font weight of this label
    },
    label : {
        display : true, // Boolean: show or hide this label
        left : 'Low', // String: The value of the left (minimum) label
        right : 'High', // String: The value of the right (maximum) label
        fontFamily : 'Arial', // String: The font family of this label
        fontColor : '#000', // String: The font color of this label
        fontSize : 12, // Integer of String: The font size of this label (without px)
        fontWeight : 'normal' // String: The font weight of this label
    }
}
```