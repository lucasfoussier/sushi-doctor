import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';

class Test extends Component {

    render() {
        return (
            <div>
                {'test'}
                <img src="assets/img/php.png" alt="Logo" />;
            </div>
        )
    }
}

export default Test;
