import {Component, OnInit, OnDestroy} from '@angular/core';
import {DataGlobalService} from '../../services/data.global.service';
import * as constants from '../../constants';
import {Subscription} from 'rxjs/Subscription';
import {TimeCongig} from '../../models/api/response/SettingReponse';
import {SettingService} from '../../services/setting.service';

declare var $: any;

@Component({
    selector: 'auth-layout',
    moduleId: module.id,
    templateUrl: 'auth.component.html'
})

export class AuthComponent implements OnInit, OnDestroy {
    sub: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    userRoles: string[] = [];
    isLoadedData = false;
    private contentHeight = 974;


    constructor(private dataGlobalService: DataGlobalService,
                private settingService: SettingService) {

    }

    ngOnDestroy(): void {
        this.sub !== undefined ? this.sub.unsubscribe() : console.log(':D');
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log(':D');
    }

    ngOnInit() {
        this.contentHeight = $(window).height() - 130;
        this.setMinHeight();
        this.sub2 = this.dataGlobalService.getInfoUser().subscribe(
            repo => {
                if (repo.status === 'success') {
                    // console.log(repo);
                    this.userRoles = repo.user.roles as string[];
                    localStorage.setItem(constants.USER_INFO, JSON.stringify(repo.user));
                    const isAdmin = repo.user.is_admin;
                    if (isAdmin) {
                        localStorage.setItem(constants.URLS_NAME, JSON.stringify(constants.LIST_URL_ROUTER));
                        this.isLoadedData = true;
                    } else {
                        this.sub = this.dataGlobalService.getPermisionUrls(repo.user.id).subscribe(
                            data => {
                                let listURL = data.urls as string[];
                                let urlMore = [];
                                if (listURL.length > 0) {
                                    listURL.forEach(item => {
                                        if (urlMore.length > 0) {
                                            let check = true;
                                            urlMore.forEach(element => {
                                                if (element === item.split('/')[1]) {
                                                    check = false;
                                                }
                                            });
                                            if (check) {
                                                urlMore.push(item.split('/')[1]);
                                            }
                                        } else {
                                            urlMore.push(item.split('/')[1]);
                                        }
                                    });
                                }
                                urlMore.forEach(element => {
                                    listURL.push('/' + element);
                                });
                                console.log(listURL);
                                localStorage.setItem(constants.URLS_NAME,
                                    JSON.stringify(listURL))
                                this.isLoadedData = true;
                                this.sub.unsubscribe();
                            },
                            error => {
                                console.log(error);
                                this.sub.unsubscribe();
                            }
                        );
                    }
                }

                this.sub2.unsubscribe();
            },
            error => {
                this.isLoadedData = true;
                this.sub2.unsubscribe();
                console.log(error);
            }
        );
        this.getTimeConfig();


    }

    setMinHeight() {
        $('#main-panel-content').css({minHeight: this.contentHeight});
    }


    private getTimeConfig() {
        this.sub3 = this.settingService.get().subscribe(
            data => {
                localStorage.setItem(constants.TIME_CONFIG, JSON.stringify(data.setting as TimeCongig));
                this.sub3.unsubscribe();
            }
            , error => {
                console.log(error);
                this.sub3.unsubscribe();
            }
        );
    }
}

