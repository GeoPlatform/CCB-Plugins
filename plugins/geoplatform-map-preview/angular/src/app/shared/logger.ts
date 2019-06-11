
import { environment } from '../../environments/environment';


class Logger {

    private isDev : boolean;

    constructor() {
        this.isDev = 'development' === environment.env;
    }

    log(arg, ...addl) {
        let msg = this.toStr(arg);
        msg += addl.map( a => this.toStr(a) ).join('');
        console.log(msg);
    }

    debug(arg, ...addl) {
        if(!this.isDev) return;
        let msg = "[DEBUG] " + this.toStr(arg);
        this.log(msg, ...addl);
    }

    error(arg, ...addl) {
        let msg = "[ERROR] " + this.toStr(arg);
        this.log(msg, ...addl);
    }

    warn(arg, ...addl) {
        let msg = "[WARN] " + this.toStr(arg);
        this.log(msg, ...addl);
    }



    toStr(arg) {
        if(null === arg || typeof(arg) === 'undefined') return '';
        if(typeof(arg) ===  'string') return arg;
        return JSON.stringify(arg);
    }
}

const logger = new Logger();

export {
    logger as default,
    logger
};
