/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Â© 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration

jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,h,e,f,g){return jQuery.easing[jQuery.easing.def](a,h,e,f,g)},easeInQuad:function(a,h,e,f,g){return f*(h/=g)*h+e},easeOutQuad:function(a,h,e,f,g){return -f*(h/=g)*(h-2)+e},easeInOutQuad:function(a,h,e,f,g){if((h/=g/2)<1){return f/2*h*h+e}return -f/2*((--h)*(h-2)-1)+e},easeInCubic:function(a,h,e,f,g){return f*(h/=g)*h*h+e},easeOutCubic:function(a,h,e,f,g){return f*((h=h/g-1)*h*h+1)+e},easeInOutCubic:function(a,h,e,f,g){if((h/=g/2)<1){return f/2*h*h*h+e}return f/2*((h-=2)*h*h+2)+e},easeInQuart:function(a,h,e,f,g){return f*(h/=g)*h*h*h+e},easeOutQuart:function(a,h,e,f,g){return -f*((h=h/g-1)*h*h*h-1)+e},easeInOutQuart:function(a,h,e,f,g){if((h/=g/2)<1){return f/2*h*h*h*h+e}return -f/2*((h-=2)*h*h*h-2)+e},easeInQuint:function(a,h,e,f,g){return f*(h/=g)*h*h*h*h+e},easeOutQuint:function(a,h,e,f,g){return f*((h=h/g-1)*h*h*h*h+1)+e},easeInOutQuint:function(a,h,e,f,g){if((h/=g/2)<1){return f/2*h*h*h*h*h+e}return f/2*((h-=2)*h*h*h*h+2)+e},easeInSine:function(a,h,e,f,g){return -f*Math.cos(h/g*(Math.PI/2))+f+e},easeOutSine:function(a,h,e,f,g){return f*Math.sin(h/g*(Math.PI/2))+e},easeInOutSine:function(a,h,e,f,g){return -f/2*(Math.cos(Math.PI*h/g)-1)+e},easeInExpo:function(a,h,e,f,g){return(h==0)?e:f*Math.pow(2,10*(h/g-1))+e},easeOutExpo:function(a,h,e,f,g){return(h==g)?e+f:f*(-Math.pow(2,-10*h/g)+1)+e},easeInOutExpo:function(a,h,e,f,g){if(h==0){return e}if(h==g){return e+f}if((h/=g/2)<1){return f/2*Math.pow(2,10*(h-1))+e}return f/2*(-Math.pow(2,-10*--h)+2)+e},easeInCirc:function(a,h,e,f,g){return -f*(Math.sqrt(1-(h/=g)*h)-1)+e},easeOutCirc:function(a,h,e,f,g){return f*Math.sqrt(1-(h=h/g-1)*h)+e},easeInOutCirc:function(a,h,e,f,g){if((h/=g/2)<1){return -f/2*(Math.sqrt(1-h*h)-1)+e}return f/2*(Math.sqrt(1-(h-=2)*h)+1)+e},easeInElastic:function(k,j,e,f,i){var g=1.70158;var h=0;var l=f;if(j==0){return e}if((j/=i)==1){return e+f}if(!h){h=i*0.3}if(l<Math.abs(f)){l=f;var g=h/4}else{var g=h/(2*Math.PI)*Math.asin(f/l)}return -(l*Math.pow(2,10*(j-=1))*Math.sin((j*i-g)*(2*Math.PI)/h))+e},easeOutElastic:function(k,j,e,f,i){var g=1.70158;var h=0;var l=f;if(j==0){return e}if((j/=i)==1){return e+f}if(!h){h=i*0.3}if(l<Math.abs(f)){l=f;var g=h/4}else{var g=h/(2*Math.PI)*Math.asin(f/l)}return l*Math.pow(2,-10*j)*Math.sin((j*i-g)*(2*Math.PI)/h)+f+e},easeInOutElastic:function(k,j,e,f,i){var g=1.70158;var h=0;var l=f;if(j==0){return e}if((j/=i/2)==2){return e+f}if(!h){h=i*(0.3*1.5)}if(l<Math.abs(f)){l=f;var g=h/4}else{var g=h/(2*Math.PI)*Math.asin(f/l)}if(j<1){return -0.5*(l*Math.pow(2,10*(j-=1))*Math.sin((j*i-g)*(2*Math.PI)/h))+e}return l*Math.pow(2,-10*(j-=1))*Math.sin((j*i-g)*(2*Math.PI)/h)*0.5+f+e},easeInBack:function(i,h,a,e,g,f){if(f==undefined){f=1.70158}return e*(h/=g)*h*((f+1)*h-f)+a},easeOutBack:function(i,h,a,e,g,f){if(f==undefined){f=1.70158}return e*((h=h/g-1)*h*((f+1)*h+f)+1)+a},easeInOutBack:function(i,h,a,e,g,f){if(f==undefined){f=1.70158}if((h/=g/2)<1){return e/2*(h*h*(((f*=(1.525))+1)*h-f))+a}return e/2*((h-=2)*h*(((f*=(1.525))+1)*h+f)+2)+a},easeInBounce:function(a,h,e,f,g){return f-jQuery.easing.easeOutBounce(a,g-h,0,f,g)+e},easeOutBounce:function(a,h,e,f,g){if((h/=g)<(1/2.75)){return f*(7.5625*h*h)+e}else{if(h<(2/2.75)){return f*(7.5625*(h-=(1.5/2.75))*h+0.75)+e}else{if(h<(2.5/2.75)){return f*(7.5625*(h-=(2.25/2.75))*h+0.9375)+e}else{return f*(7.5625*(h-=(2.625/2.75))*h+0.984375)+e}}}},easeInOutBounce:function(a,h,e,f,g){if(h<g/2){return jQuery.easing.easeInBounce(a,h*2,0,f,g)*0.5+e}return jQuery.easing.easeOutBounce(a,h*2-g,0,f,g)*0.5+f*0.5+e}});

/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright Â© 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */