import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";

@Component({
    selector: 'acount-cmp',
    moduleId: module.id,
    templateUrl: 'acount.component.html'
})

export class AcountComponent implements OnInit {

    form: FormGroup
    ngOnInit() {
    }

    // SHOW OR HIDE TÌM KIẾM NÂNG CAO
    public showAdvanced: boolean = false;
    public btnShowAdvanced: any = 'Tìm Kiếm nâng cao';
    public btnShowAdvancedIcon: any = 'ti-angle-double-down';
    toggle() {
        this.showAdvanced = !this.showAdvanced;

        // CHANGE THE NAME OF THE BUTTON.
        if (this.showAdvanced) {
            this.btnShowAdvancedIcon = "ti-angle-double-up"
            this.btnShowAdvanced = "Tìm kiếm đơn giản";
        }
        else {
            this.btnShowAdvancedIcon = "ti-angle-double-down"
            this.btnShowAdvanced = "Tìm kiếm nâng cao";
        }

    }
    // SET ARRAY TÌM KIẾM NÂNG CAO
     //set array select option
     public rules: any[] = [{ id: 'QT', value: 'Quản trị' }, { id: 'mb', value: 'Người dùng' },];
     public statuss: any[] = [{ id: 'DN', value: 'Đã nghỉ' }, { id: 'Dlv', value: 'Đang làm việc' },];
     public dislayhumans: any[] = [{ id: '5', value: '5' }, { id: '10', value: '10' },{ id: '15', value: '15' },{ id: '20', value: '20' },];
     curentRule: any = this.rules[0];
     curentStatus: any = this.statuss[0];
     curentDislay: any = this.dislayhumans[0];
 
     setRuleSelect(id: any): void {
         this.curentRule = this.rules.filter(value => value.id === parseInt(id));
     }
     setStatusSelect(id: any): void {
         this.curentStatus = this.statuss.filter(value => value.id === parseInt(id));
     }
    
     setDislaySelect(id: any): void {
         this.curentDislay = this.dislayhumans.filter(value => value.id === parseInt(id));
     }

}