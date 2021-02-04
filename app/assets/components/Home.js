import React, {Component} from 'react';
import {Route, Switch,Redirect, Link, withRouter} from 'react-router-dom';
import Test from "./Test";
class Home extends Component {

    render() {
        return (
            <div>
                {'fdsqfd'}
                <img src="assets/img/php.png" alt="Logo" />;
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className={"navbar-brand"} to={"/"}> Symfony React Project </Link>
                    <div className="collapse navbar-collapse" id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/test"}> Test </Link>
                            </li>

                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/"}> home </Link>
                            </li>
                        </ul>
                    </div>
                </nav>

                <Switch>
                    <Redirect exact from="/" to="/" />
                    <Route path="/test" component={Test} />
                </Switch>

            </div>
        )
    }
}

export default Home;
