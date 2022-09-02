const translateBtn = document.querySelector("#translate"), 
fromText = document.querySelector("#from-text"),
languages = document.querySelector("#languages");

translateBtn.addEventListener("click", () => {
    let from_id = 0;
    JSON.parse(languages.value).forEach(element => {
      //console.log(element);
      if (!from_id) {
        from_id = element;
      } else {
        panelTranslate(from_id, element);
      }
    });
});

const panelTranslate = (from_id, panel_id) => {
    const panel = document.querySelector("#collapse_" + panel_id),
    //fromText = document.querySelector("#from-text"),
    toText = document.querySelector("#to-text_" + panel_id),
    fromDesc = document.querySelector("#editor_" + from_id),
    toDesc = document.querySelector("#editor_" + panel_id);
    toText.setAttribute("placeholder", "Translating...");
    translate (fromText.value.trim(), fromText.dataset.lang, toText.dataset.lang)
    .then(tr2 => { toText.value = tr2 });
  
    const desc = tinymce.get(fromDesc.id).getContent();
    //console.log(desc);
    
    if(desc) translate (desc, fromDesc.dataset.lang, toDesc.dataset.lang)
    .then(tr2 => tinymce.get(toDesc.id).setContent(tr2));
    
    panel.classList.add('in');
}


const translate = (text, from, to) => {
  if(!text) return;
  text = encodeURIComponent(text); 
  let apiUrl = `https://api.mymemory.translated.net/get?q=${text}&langpair=${from}|${to}&de=admin@emarto.ru`;
  let tr = fetch(apiUrl).then(res => res.json()).then(data => {
      return data.responseData.translatedText;
          
  });
  return tr;
}