import SiteModule from "@/services/SiteModule";
import 'aos/dist/aos.css';
import AOS from 'aos';

export function init() {
    AOS.init({
        duration: 1000,
        easing: "ease-in-out",
        once: true,
        mirror: false
    });
}