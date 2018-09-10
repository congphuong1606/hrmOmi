import {Injectable} from '@angular/core';
import * as firebase from 'firebase';
import 'rxjs/add/operator/take';
import {BehaviorSubject} from 'rxjs/BehaviorSubject';
import {HttpClient} from '@angular/common/http';
import * as constants from '../../constants';

@Injectable()
export class FcmserviceService {
    messaging = firebase.messaging()
    currentMessage = new BehaviorSubject(null)

    constructor(public http: HttpClient
    ) {
    }


    updateToken(token) {
        const bodyToken = {
            fcm_device_token: token,
            type: 2,
        };
        console.log('api/devices:' + token);
        this.http.post<any>('api/devices', bodyToken).subscribe(
            data => {
                localStorage.setItem(constants.DEVICE_ID, data.device.id);
            }, b => {
            }
        );
    }

    getPermission() {
        this.messaging.requestPermission()
            .then(() => {
                return this.messaging.getToken();
            })
            .then(token => {
                this.updateToken(token);
            })
            .catch((err) => {
                localStorage.setItem(constants.DEVICE_ID, '');
                console.log('Unable to get permission to notify.', err);
            });
    }

    receiveMessage() {
        this.messaging.onMessage((payload) => {
            this.currentMessage.next(payload);
        });

    }

}
