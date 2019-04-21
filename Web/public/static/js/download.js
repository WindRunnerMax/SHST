function saveJPG(src) { 
　　if (document.all.a1 == null) { 
　　　　objIframe = document.createElement("IFRAME"); 
　　　　document.body.insertBefore(objIframe); 
　　　　objIframe.outerHTML = "<iframe name=a1 style='width:400px;hieght:300px' src=" + src + "></iframe>"; 
　　　　re = setTimeout("savepic()", 1) 
　　} else { 
　　　　clearTimeout(re) 
　　　　pic = window.open(imageName.href, "a1") 
　　　　pic.document.execCommand("SaveAs") 
　　　　document.all.a1.removeNode(true) 
　　} 
}
