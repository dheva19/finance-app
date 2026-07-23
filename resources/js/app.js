import Swal from 'sweetalert2';

window.Swal = Swal;

document.addEventListener("DOMContentLoaded", function(){
    const mobileQuery = window.matchMedia('(max-width: 768px)');
    if(mobileQuery.matches){
        document.querySelector('#sidebar').classList.add('hidden');
    }

    function toggleSidebar(){
        const sidebar = document.querySelector('#sidebar');
        const navbar = document.querySelector('#navbar');
        const mainContent = document.querySelector('#main-content');

        if(sidebar.classList.contains('hidden')){
            sidebar.classList.remove('hidden');
            if(!mainContent.classList.contains('md:ps-65')){
                mainContent.classList.add('md:ps-65')
            }
            if(!navbar.classList.contains('md:ps-65')){
                navbar.classList.add('md:ps-65');
            }
        }else{
            sidebar.classList.add('hidden');
            if(mainContent.classList.contains('md:ps-65')){
                mainContent.classList.remove('md:ps-65');
            }
            if(navbar.classList.contains('md:ps-65')){
                navbar.classList.remove('md:ps-65');
            }
        }
    }

    document.querySelector('#toggle-sidebar').addEventListener('click', function(){
        toggleSidebar();
    });

    document.querySelector('#sidebar-overlay').addEventListener('click', function(){
        toggleSidebar();
    });


    

});

