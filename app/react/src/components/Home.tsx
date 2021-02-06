import React, {Component} from 'react';
import logo from '../../img/logo.svg';
class Home extends Component {
    render() {
        return (
            <div>
                {'home'}
                <img src={logo} alt="Logo" />
            </div>
        )
    }
}
export default Home;
