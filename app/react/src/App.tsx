import React from 'react';
import './App.css';
import {
  Link, Redirect, Route, Switch,
} from 'react-router-dom';
import Test from '@components/Test';
import Home from '@components/Home';
import NotFound from '@container/NotFound';

function App(): JSX.Element {
  return (
    <>
      <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
        <Link className="navbar-brand" to="/"> Symfony React Project </Link>
        <div className="collapse navbar-collapse" id="navbarText">
          <ul className="navbar-nav mr-auto">
            <li className="nav-item">
              <Link className="nav-link" to="/test"> Test </Link>
            </li>

            <li className="nav-item">
              <Link className="nav-link" to="/"> home </Link>
            </li>
          </ul>
        </div>
      </nav>
      <Switch>
        <Redirect exact from="/" to="/home" />
        <Route path="/home" component={Home} />
        <Route path="/test" component={Test} />
        <Route>
          <NotFound />
        </Route>
      </Switch>
    </>

  );
}

export default App;
