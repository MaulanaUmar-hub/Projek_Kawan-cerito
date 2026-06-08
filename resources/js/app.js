```js
import './bootstrap';

import lucide from 'lucide';

/*
|--------------------------------------------------------------------------
| Lucide Icons
|--------------------------------------------------------------------------
*/

lucide.createIcons();

/*
|--------------------------------------------------------------------------
| Navbar Shadow on Scroll
|--------------------------------------------------------------------------
*/

const navbar = document.getElementById('navbar');

if (navbar) {
    window.addEventListener('scroll', () => {

        if (window.scrollY > 20) {
            navbar.classList.add('shadow-lg');
        } else {
            navbar.classList.remove('shadow-lg');
        }

    });
}
```
