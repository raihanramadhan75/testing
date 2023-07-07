class Header extends React.Component {
	render() {
		return <h1>Sabacota Dashboard</h1>;
	}
}

var domContainer = document.querySelector('#header');
ReactDOM.render(<Header />, domContainer);
