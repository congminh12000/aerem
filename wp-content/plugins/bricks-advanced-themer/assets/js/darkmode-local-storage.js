(function() {
    let darkmodeCookie = localStorage.getItem("brxc-theme");
    if(darkmodeCookie === 'dark' || (!darkmodeCookie && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme','dark');
    } 
})();