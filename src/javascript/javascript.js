function openModal(id) {
    document.getElementById(id).style.display = "block";
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

window.onclick = function(event) {
    let modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
}

function switchModal(current, next) {
    closeModal(current);
    openModal(next);
}

document.addEventListener('DOMContentLoaded', () => {
    const leftBtn = document.querySelector('.carousel-btn.left');
    const rightBtn = document.querySelector('.carousel-btn.right');
    const track = document.querySelector('.carousel-track');
    const videos = Array.from(track.children);

    let order = [0,1,2];

    function updateCarousel() {
        videos.forEach(v => v.classList.remove('left','center','right'));

        videos[order[0]].classList.add('left');
        videos[order[1]].classList.add('center');
        videos[order[2]].classList.add('right');

        track.style.justifyContent = 'center';
    }

    leftBtn.addEventListener('click', () => {
        order.unshift(order.pop());
        updateCarousel();
    });

    rightBtn.addEventListener('click', () => {
        order.push(order.shift());
        updateCarousel();
    });

    updateCarousel();
});

function toggleMenu() {
    const menu = document.getElementById('menu-deroulant');
    menu.classList.toggle('active');
}

// Ferme le menu si on clique ailleurs
window.addEventListener('click', function(e) {
    const menu = document.getElementById('menu-deroulant');
    const burger = document.querySelector('.menu-burger');
    if (!menu.contains(e.target) && !burger.contains(e.target)) {
        menu.classList.remove('active');
    }
});