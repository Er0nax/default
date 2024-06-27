import Swapper from '@/controllers/Swapper';

class Main {
    constructor() {
        Swapper.start();
    }
}

const main = new Main();
