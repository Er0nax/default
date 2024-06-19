import {Site} from '@/types';
/// <reference types="vite/client" />
export {};
declare global {
    interface Window {
        Site: Site;
        _paq?: any[]
    }
}