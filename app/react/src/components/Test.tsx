import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import logoPng from '../../img/php.png';

class Test extends Component {

    render() {
        return (
            <div>
                {'test'}
                <img src={logoPng} alt="Logo" />;
            </div>
        )
    }
}

export default Test;
