import {
    ReactiveFormsModule,
    NG_VALIDATORS,
    FormsModule,
    FormGroup,
    FormControl,
    ValidatorFn,
    Validator,
    AbstractControl,
    ValidationErrors
} from '@angular/forms';
import { Directive } from '@angular/core';
import { PersonnelService } from '../services/personnel.service';
function isEmptyInputValue(value: any): boolean {
    // we don't check for string here so it also works with arrays
    return value == null || value.length === 0;
}
const EMAIL_REGEXP = /^(?=.{1,254}$)(?=.{1,64}@)[-!#$%&'*+/0-9=?A-Z^_`a-z{|}~]+(\.[-!#$%&'*+/0-9=?A-Z^_`a-z{|}~]+)*@[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?(\.[A-Za-z0-9]([A-Za-z0-9-]{0,61}[A-Za-z0-9])?)*$/;
export class CommonValidator {
    validator: ValidatorFn;

    static isNumber(control: AbstractControl): ValidationErrors | null {
        const isValid = /^[1-9][0-9]*$/.test(control.value);
        if (isValid) {
            return null;
        } else {
            return {
                'isNumber': true
            };
        }
    }

    static isNumberFrom0(control: AbstractControl): ValidationErrors | null {
        const isValid = /^[0-9][0-9]*$/.test(control.value);
        if (isValid) {
            return null;
        } else {
            return {
                'isNumberFrom0': true
            };
        }
    }

    static isFloatFrom0(control: AbstractControl): ValidationErrors | null {
        const isValid = /^\d*(\.\d+)?$/.test(control.value);
        if (isValid) {
            return null;
        } else {
            return {
                'isFloatFrom0': true
            };
        }
    }

    static isNumberNullable(control: AbstractControl): ValidationErrors | null {
        const isValid = /^[1-9][0-9]*$/.test(control.value);
        if (isValid || isEmptyInputValue(control.value)) {
            return null;
        } else {
            return {
                'isNumberNullable': true
            };
        }
    }

    static isNumberFrom0Nullable(control: AbstractControl): ValidationErrors | null {
        const isValid = /^[0-9][0-9]*$/.test(control.value);
        if (isValid || isEmptyInputValue(control.value)) {
            return null;
        } else {
            return {
                'isNumberFrom0Nullable': true
            };
        }
    }

    /**
 * Validator that performs email validation.
 */
    static emailNullable(control: AbstractControl): ValidationErrors | null {
        if (isEmptyInputValue(control.value)) {
            return null;  // don't validate empty values to allow optional controls
        }
        return EMAIL_REGEXP.test(control.value) ? null : { 'emailNullable': true };
    }

    static isValidPassword(control: AbstractControl): ValidationErrors | null {
        const isValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/.test(control.value);
        if (isValid) {
            return null;
        } else {
            return {
                'isValidPassword': true
            };
        }
    }

    static isEmailExistCreate(personnelService: PersonnelService) {
        return (control: AbstractControl) => {
            return personnelService.searchEmailExistCreate({email: control.value}).map(res => {
                return res.result === 'valid' ? null : { isEmailExistCreate: true };
            });
        };
    }

    static isEmailExistUpdate(personnelService: PersonnelService, employeeId) {
        return (control: AbstractControl) => {
            return personnelService.searchEmailExistUpdate({email: control.value, employee_id: employeeId}).map(res => {
                return res.result === 'valid' ? null : { isEmailExistUpdate: true };
            });
        };
    }

    static isEmployeeCodeExistCreate(personnelService: PersonnelService) {
        return (control: AbstractControl) => {
            return personnelService.searchEmployeeCodeExistCreate({employee_code: control.value}).map(res => {
                return res.result === 'valid' ? null : { isEmployeeCodeExistCreate: true };
            });
        };
    }

    static isEmployeeCodeExistUpdate(personnelService: PersonnelService, employeeId) {
        return (control: AbstractControl) => {
            return personnelService.searchEmployeeCodeExistUpdate({employee_code: control.value, employee_id: employeeId}).map(res => {
                return res.result === 'valid' ? null : { isEmployeeCodeExistUpdate: true };
            });
        };
    }
    
    static isValidNumberAccumulated(control: AbstractControl): ValidationErrors | null {
        const isValid = /^[+-]?([0-9]*[.])?[0-9]+$/.test(control.value);
        if (isValid) {
            return null;
        } else {
            return {
                'isValidNumberAccumulated': true
            };
        }
    }
}
