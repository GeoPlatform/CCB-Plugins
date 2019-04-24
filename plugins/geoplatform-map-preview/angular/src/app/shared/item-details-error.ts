
export class ItemDetailsError extends Error {

    public label : string;
    public status : number = 500;

    constructor(message : string, status?:number) {
        super(message);
        if(status)
            this.status = status;
    }

}
