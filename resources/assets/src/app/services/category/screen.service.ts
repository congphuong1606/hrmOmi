import {Injectable} from '@angular/core';

import {Http, Response, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import * as jwt_decode from 'jwt-decode';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Router} from '@angular/router';
import {AuthService} from '../authSevice';
import {Screen, ListScreenRepo, ListScrCategory, ScreenCategoryRepo, ScreenRepo} from '../../models/api/category-response/ScreenReponse';

declare var swal: any;
export const TOKEN_NAME: string = 'jwt_token';

@Injectable()
export class ScreenCategoryService {
    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getListScreen() {
        return this.http.get<ListScreenRepo>('api/screen');
    }

    getScreen(id): Promise<Screen> {
        const url = 'api/screen/' + id;
        return this.http.get(url)
            .toPromise()
            .then(res => (res as ScreenRepo).screen as Screen)
            .catch(this.handleError);

    }

    createScreen(body: any,): Promise<any> {

        console.log('TẠO MÀN HÌNH KHÔNG THÊM LIST URL')
        return this.http.post('api/screen', body)
            .toPromise()
            .then(response => response)
            .catch(this.handleError);


    }

    updateScreen(body: any, id: Number): Promise<any> {
        return this.http.put('api/screen/' + id, body)
            .toPromise()
            .then(response => response)
            .catch(this.handleError);


    }

    getListScrCategory(): Promise<ListScrCategory> {
        return this.http.get('api/screen_category')
            .toPromise()
            .then(response => (response as ListScrCategory))
            .catch(this.handleError);
    }

    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error);
        return Promise.reject(error.message || error);
    }

    createScrCategory(categoryname: any): Promise<ScreenCategoryRepo> {
        return this.http.post('api/screen_category', {name: categoryname, display_name: categoryname, description: categoryname})
            .toPromise()
            .then(response => (response as ScreenCategoryRepo))
            .catch(this.handleError);
    }

    delete(id: number): Promise<any> {
        let url = 'api/screen/' + id;
        return this.http.delete(url)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }


    // URL


    getListUrlOfScreen(id: any): Promise<any> {
        let url = 'api/screen/' + id + '/urls';
        return this.http.get(url).toPromise().then(res => res).catch(this.handleError);
    }

    getListUrls(): Promise<any> {
        let url = 'api/urls';
        return this.http.get(url).toPromise().then(res => res).catch(this.handleError);
    }

    attachUrls(link: any, id: any): Promise<any> {
        let url = 'api/screen/' + id + '/attach/urls?' + link;
        return this.http.get(url).toPromise().then(res => res).catch(this.handleError);
    }

}