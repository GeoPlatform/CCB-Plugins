
import { environment } from '../../environments/environment';

const LEVELS = [
    "error",
    "warn",
    "info",
    "debug"
];

class Logger {

    private isDev : boolean;
    private level : string = 'error';

    constructor() {
        this.isDev = 'development' === environment.env;

        if(environment.logLevel) {
            this.level = environment.logLevel;
        }
        this.log("Log Level : " + this.level);
    }

    isVisible(level : string) : boolean {
        return LEVELS.indexOf(this.level) >= LEVELS.indexOf(level);
    }

    log(arg, ...addl) {
        let msg = this.toStr(arg);
        msg += addl.map( a => this.toStr(a) ).join('');
        console.log(msg);
    }

    debug(arg, ...addl) {
        if(!this.isVisible('debug')) return;
        let msg = "[DEBUG] " + this.toStr(arg);
        this.log(msg, ...addl);
    }

    info(arg, ...addl) {
        if(!this.isVisible('info')) return;
        let msg = "[INFO] " + this.toStr(arg);
        this.log(msg, ...addl);
    }

    warn(arg, ...addl) {
        if(!this.isVisible('warn')) return;
        let msg = "[WARN] " + this.toStr(arg);
        this.log(msg, ...addl);
    }

    error(arg, ...addl) {
        let msg = "[ERROR] " + this.toStr(arg);
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
