import {reactive, unref, toRaw} from 'vue'

interface Ages {
    min?: Number
    max?: Number
}

export const store = reactive({
    category: [],
    gender: [],
    birthDate: null,
    age: {} as Ages,
    setAge(min, max?) {
        this.birthDate = null;
        this.age.min = parseInt(min);
        if (max) {
            this.age.max = parseInt(max);
        }
        if (this.age.min > this.age.max) {
            this.age.max = this.age.min;
        }
    },
    setBirthDate(event) {
        this.age = {};
        this.birthDate = event;
    },
    resetDates() {
        this.birthDate = null;
        this.age = {};
    },
    setItem(name, value) {
        const index = this[name].indexOf(value);
        if (index === -1) {
            this[name].push(value);
        }

    },
    removeItem(name, value) {
        const index = this[name].indexOf(value);
        if (index > -1) {
            this[name].splice(index, 1);
        }

    },
    removeAll(name) {
        this[name] = [];
    },
    queryString() {
        var query = new URLSearchParams();

        if (this.category.length) {
            query.append('category', this.category);
        }
        if (this.gender.length) {
            query.append('gender', this.gender);
        }

        if (this.birthDate !== null) {
            query.append('birthDate', this.birthDate)
        }
        if (toRaw(this.age)) {
            var ageValue = toRaw(this.age);
            if (ageValue.min > 0) {
                var ageRef = [ageValue.min];
                if (ageValue.max && ageValue.max > ageValue.min) {
                    ageRef.push(ageValue.max);
                }
                query.append('age', ageRef.join(','));
            }
        }
        return query.toString();
    },
})
