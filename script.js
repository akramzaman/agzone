```javascript
document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.right-bar');
    const mobileNav = document.querySelector('.mobile_nav');

    hamburger.addEventListener('click', function () {
        mobileNav.style.display = mobileNav.style.display === 'block' ? 'none' : 'block';
    });

    // Adjust navigation based on screen size
    function adjustNavigation() {
        if (window.innerWidth <= 768) {
            hamburger.style.display = 'block';
            document.querySelector('.right-side').style.display = 'none';
        } else {
            hamburger.style.display = 'none';
            document.querySelector('.right-side').style.display = 'flex';
            mobileNav.style.display = 'none';
        }
    }

    adjustNavigation();
    window.addEventListener('resize', adjustNavigation);
});
```