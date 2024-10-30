document.addEventListener("DOMContentLoaded", () => {

  if (window.koi_url) {
    if (window.KoiEmbed) {
      KoiEmbedInit();
    } else {
      loadScript();
    }

    const wpAdminBar = document.getElementById('wpadminbar');
    if (wpAdminBar){
      appendStyle ('.koi-cart-header { margin-top: ' + wpAdminBar.offsetHeight / 2 + 'px; }');
    }

  } else {
    const collections = document.querySelectorAll('.koi-collection')

    for(const element of collections) {
      element.innerHTML = "Koi settings are not configured."
    }
  }

})

function loadScript() {
  const script = document.createElement('script');
  script.async = true;
  script.src = window.koi_url + '/koi-embed.js';
  (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
  script.onload = KoiEmbedInit;
}
function KoiEmbedInit() {
  window.KoiEmbed.init({
    koiUrl: window.koi_url,
    embedFrontToken: window.koi_front_token,
    mode: 'cart'
  })
}
function appendStyle (css) {
  const style = document.createElement('style');
  style.type = 'text/css';
  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }
  document.getElementsByTagName('head')[0].appendChild(style);
}