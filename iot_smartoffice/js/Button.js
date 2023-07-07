/*
	Author : Budiarto 2018
	Copyright : Konekthing 2018
*/
'use strict';

const e = React.createElement;

class Button extends React.Component {
  constructor(props) {
    super(props);
    this.state = { liked: false };
  }

  render() {
    if (this.state.liked) {
      return 'Get Data';
    }

    return e(
      'button',
      { onClick: () => this.setState({ liked: true }) },
      'Ambil Data Sabacota'
    );
  }
}

const domContainer = document.querySelector('#button');
ReactDOM.render(e(Button), domContainer);
