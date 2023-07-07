'use strict';

import React from "react";

class Menubar extends React.Component {
	render () {
		return (
			<div className="panel">
			<div className="fa fa-more"></div>
			<span className="title">Timeline</span>
			<input type="text" className="searchinput" placeholder="search"/>
			</div>
		)
	}
}

var domContainer = document.querySelector('#menubar');
ReactDOM.render(<Menubar />, domContainer);
