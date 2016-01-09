var dialog		= window.parent ;
var oEditor		= dialog.InnerDialogLoaded() ;
var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;
var FCKTools	= oEditor.FCKTools ;

// Function called when a dialog tag is selected.
function OnDialogTabChange( tabCode )
{
//ShowE('divInfo'		, ( tabCode == 'Info' ) ) ;
}

// Get the selected flash embed (if available).
var oFakeMedia = dialog.Selection.GetSelectedElement() ;
var oEmbed ;

if ( oFakeMedia )
{
    if ( oFakeMedia.tagName == 'IMG' && oFakeMedia.getAttribute('_fckmedia') )
        oEmbed = FCK.GetRealElement( oFakeMedia ) ;
    else
        oFakeMedia = null ;
}

window.onload = function()
{
    // Translate the dialog box texts.
    oEditor.FCKLanguageManager.TranslatePage(document) ;

    // Load the selected element information (if any).
    LoadSelection() ;

    dialog.SetAutoSize( true ) ;

    // Activate the "OK" button.
    dialog.SetOkButton( true ) ;

    SelectField( 'txtUrl' ) ;
}

function LoadSelection()
{
    if ( ! oEmbed ) return ;

    GetE('txtUrl').value    = GetAttribute( oEmbed, 'value', '' ) ;
    GetE('txtWidth').value  = GetAttribute( oEmbed, 'width', '' ) ;
    GetE('txtHeight').value = GetAttribute( oEmbed, 'height', '' ) ;
    // Get Advances Attributes
    GetE('chkAutoPlay').checked	= GetAttribute( oEmbed, 'play', 'true' ) == 'true' ;
    GetE('chkLoop').checked		= GetAttribute( oEmbed, 'loop', 'true' ) == 'true' ;

    UpdatePreview() ;
}

//#### The OK button was hit.
function Ok()
{
    if ( GetE('txtUrl').value.length == 0 )
    {
        GetE('txtUrl').focus() ;

        alert( oEditor.FCKLang.DlgAlertUrl ) ;

        return false ;
    }

    oEditor.FCKUndo.SaveUndoStep() ;
    if ( !oEmbed )
    {
        oEmbed		= FCK.EditorDocument.createElement( 'EMBED' ) ;
        oFakeMedia  = null ;
    }
    UpdateEmbed( oEmbed ) ;

    if ( !oFakeMedia ){
        oFakeMedia	= oEditor.FCKDocumentProcessor_CreateFakeImage( 'FCK__Media', oEmbed ) ;
        oFakeMedia.setAttribute( '_fckmedia', 'true', 0 ) ;
        oFakeMedia	= FCK.InsertElement( oFakeMedia ) ;
    }

    oEditor.FCKEmbedAndObjectProcessor.RefreshView( oFakeMedia, oEmbed ) ;

    return true ;
}

function UpdateEmbed( e ,w,h)
{
    SetAttribute( e, 'type' , 'application/x-mplayer2' ) ;
    if(WinPlayer(GetE('txtUrl').value)!=null){
        SetAttribute( e, 'type' , 'application/x-mplayer2' ) ;
        SetAttribute( e, 'autostart', GetE('chkAutoPlay').checked ? 'true' : 'false' ) ;
    }
    if(RealPlayer(GetE('txtUrl').value)!=null){
        SetAttribute( e, 'type' , 'audio/x-pn-realaudio-plugin' ) ;
        SetAttribute( e, 'autostart', GetE('chkAutoPlay').checked ? 'true' : 'false' ) ;
    }
    if(QuickTime(GetE('txtUrl').value)!=null){
        SetAttribute( e, 'type' , 'video/quicktime' ) ;
        SetAttribute( e, 'autostart', GetE('chkAutoPlay').checked ? 'true' : 'false' ) ;
    }
    if(FlashPlayer(GetE('txtUrl').value)!=null){
        SetAttribute( e, 'type' , 'application/x-shockwave-flash' ) ;
        SetAttribute( e, 'pluginspage' , 'http://www.macromedia.com/go/getflashplayer ' ) ;
    }
    SetAttribute( e, 'src', GetE('txtUrl').value ) ;
    SetAttribute( e, "width" , w?w:GetE('txtWidth').value ) ;
    SetAttribute( e, "height", h?h:GetE('txtHeight').value ) ;
    // Advances Attributes
    SetAttribute( e, 'loop', GetE('chkLoop').checked ? 'true' : 'false' ) ;
}

var ePreview ;

function SetPreviewElement( previewEl )
{
    ePreview = previewEl ;

    if ( GetE('txtUrl').value.length > 0 )
        UpdatePreview() ;
}

function UpdatePreview()
{
    if ( !ePreview )
        return ;

    while ( ePreview.firstChild )
        ePreview.removeChild( ePreview.firstChild ) ;

    if ( GetE('txtUrl').value.length == 0 )
        ePreview.innerHTML = '&nbsp;' ;
    else
    {
        var oDoc	= ePreview.ownerDocument || ePreview.document ;
        var e		= oDoc.createElement( 'EMBED' ) ;
        UpdateEmbed( e ,"100%","100%")
        ePreview.appendChild( e ) ;
    }
}

function SetUrl( url, width, height )
{
    GetE('txtUrl').value = url ;

    if ( width )
        GetE('txtWidth').value = width ;

    if ( height )
        GetE('txtHeight').value = height ;

    UpdatePreview() ;
}
function WinPlayer(url){
    var r, re;
    re = /.(avi|wmv|asf|wma|mid|mp3|mpg)$/i;
    r = url.match(re);
    return r;
}
 
function RealPlayer(url){
    var r, re;
    re = /.(.rm|.ra|.rmvb|ram)$/i;
    r = url.match(re);
    return r;
}
 
function QuickTime(url){
    var r, re;
    re = /.(mov|qt)$/i;
    r = url.match(re);
    return r;
}
 
function FlashPlayer(url){
    var r, re;
    re = /.swf$/i;
    r = url.match(re);
    return r;
}