import Swapper from '@/controllers/Swapper';
import SiteModule from "@/shared/SiteModule";

class Main {
    constructor() {
        if (SiteModule.useBootstrap) {
            // Dynamic import of Bootstrap
            import('bootstrap/dist/css/bootstrap.min.css');
        }

        Swapper.start();
    }
}

const main = new Main();
