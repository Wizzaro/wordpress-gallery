var Wizzaro=Wizzaro||{};Wizzaro.namespace=Wizzaro.namespace||function(a){var b,c,d=a.split("."),e=Wizzaro;for("Wizzaro"==d[0]&&(d=d.slice(1)),b=0,c=d.length;b<c;b++)"undefined"==jQuery.type(e[d[b]])&&(e[d[b]]={}),e=e[d[b]];return e},Wizzaro.namespace("Plugins.Gallery.v1.Loader"),Wizzaro.Plugins.Gallery.v1.Loader=function(a,b){function c(a,b){i++,"error_load_image"==a.type&&b.remove(),f()}function d(){m.css("width",i/j*100+"%")}function e(){n.html(i+" / "+j)}function f(){i==j&&(l.slideUp(0),setTimeout(g,0))}function g(){k.show(),setTimeout(function(){k.masonry({itemSelector:a.image_item})},0)}var h=b(this),i=0,j=0,k=b(a.container),l=k.find(a.loader),m=l.find(a.loader_progressbar_value),n=l.find(a.loader_images_number_info),k=k.find(a.images_container);_images=k.find(a.image_item),j=_images.length,j>0&&(h.on("load_image",c),h.on("error_load_image",c),n.length>0&&(e(),h.on("load_image",e),h.on("error_load_image",e)),m.length>0&&(h.on("load_image",d),h.on("error_load_image",d)),b.each(_images,function(a,c){var d=b(new Image);d.on("load",function(){h.trigger("load_image",[b(c)])}),d.on("error",function(){h.trigger("error_load_image",[b(c)])}),d.attr("src",b(c).find("img").attr("src"))}))},jQuery(document).ready(function(a){new Wizzaro.Plugins.Gallery.v1.Loader({container:".wizzaro-gallery-images",loader:".wgi-loader",loader_progressbar_value:".progressbar .progressbar-value",loader_images_number_info:".number-of-images",images_container:".wgi-items",image_item:".wgi-item"},a)});