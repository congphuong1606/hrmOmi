export class HelperFunction {
    public static required(val) {
        if (val && val !== null && val !== '') {
            return true;
        }
        return false;
    }
}