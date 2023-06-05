

document.getElementById('switchTheme').addEventListener('click',()=>{
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        document.documentElement.setAttribute('data-bs-theme','light')
        document.getElementById('switchThemeIcon').classList.add('fa-moon');
        document.getElementById('switchThemeIcon').classList.remove('fa-sun');
    }
    else {
        document.documentElement.setAttribute('data-bs-theme','dark')
        document.getElementById('switchThemeIcon').classList.add('fa-sun');
        document.getElementById('switchThemeIcon').classList.remove('fa-moon');
    }
})