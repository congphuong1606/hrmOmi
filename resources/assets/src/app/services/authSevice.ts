import {Injectable} from '@angular/core';
import {Http, Response, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import * as jwt_decode from 'jwt-decode';
import {HttpClient} from '@angular/common/http';
import {Router} from '@angular/router';

export const TOKEN_NAME: string = 'jwt_token';

@Injectable()
export class AuthService {

    private url: string = 'api/auth';
    private headers = new Headers({'Content-Type': 'application/json'});

    constructor(private http: Http, private router: Router) {
    }

    getToken(): string {
        return localStorage.getItem(TOKEN_NAME);
    }

    setToken(token: string): void {
        localStorage.setItem(TOKEN_NAME, token);
    }

    getTokenExpirationDate(token: string): Date {
        const decoded = jwt_decode(token);

        if (decoded.exp === undefined) return null;

        const date = new Date(0);
        date.setUTCSeconds(decoded.exp);
        return date;
    }

    isTokenExpired(token?: string): boolean {
        if (!token) token = this.getToken();
        if (!token) return true;

        const date = this.getTokenExpirationDate(token);
        if (date === undefined) return false;
        return !(date.valueOf() > new Date().valueOf());
    }

    login(user): Promise<any> {
        const apiURL = `${this.url}/login`;
        return this.http.post(apiURL, {email: user.email, password: user.password})
            .toPromise()
            .then(res => res);
    }


    sendPasswordResetEmail(value: any) {
        return this.http.post('api/auth/password/forget', {email: value.email})
            .toPromise()
            .then(res => res);
    }

    resetPass(param: any) {
        return this.http.post('api/auth/password/forget/verify', param)
            .toPromise()
            .then(res => res);

    }
}

