
import { Query, QueryParameters } from 'geoplatform.client';

import {
    Constraint,
    MultiValueConstraint,
    CompoundConstraint,
    Constraints
} from './constraint';

describe('Constraint', () => {

    it('should work', () => {

        let constraint = new Constraint('test', 'value', 'Label');
        expect(constraint).toBeTruthy();
        expect(constraint.name).toEqual('test');
        expect(constraint.value).toEqual('value');
        expect(constraint.label).toEqual('Label');

        constraint.set('updated');
        expect(constraint.value).toEqual('updated');

        let c2 = new Constraint('test', 'another');
        constraint.update(c2);
        expect(constraint.value).toEqual('another');

    });

    it("should support multi-value constraints", () => {

        let constraint = new MultiValueConstraint('test', ['value1','value2'], 'Label');
        expect(constraint).toBeTruthy();
        expect(constraint.name).toEqual('test');
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(2);
        expect(constraint.label).toEqual('Label');

        constraint.set("array");
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(1);

        constraint.remove('array');
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(0);

    });

    it("should support compount constraints", () => {

        let c1 = new Constraint('c1', 'value1', 'Label');
        let c2 = new Constraint('c2', 'value2', 'Label');
        let constraint = new CompoundConstraint('test', [c1,c2], 'Label');
        expect(constraint).toBeTruthy();
        expect(constraint.name).toEqual('test');
        expect(constraint.value).toBeTruthy();
        expect(constraint.value.length).toEqual(2);
        expect(constraint.label).toEqual('Label');

    });

    it("should hold all constraints", () => {

        let constraints = new Constraints();
        let c1 = new Constraint('c1', 'value1', 'Label');
        let c2 = new Constraint('c2', 'value2', 'Label');
        constraints.set(c1);
        constraints.set(c2);

        expect(constraints.list().length).toEqual(2);
        constraints.unset(c2);
        expect(constraints.list().length).toEqual(1);
        constraints.clear();
        expect(constraints.list().length).toEqual(0);
    });

});
