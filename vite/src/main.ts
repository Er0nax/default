import Swapper from '@/controllers/Swapper';

class Main {
    constructor() {
        const swapper = new Swapper();
        swapper.start();
    }
}

const main = new Main();