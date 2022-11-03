import React, { useState, useEffect } from 'react';
import io from 'socket.io-client';

const socket = io();

function App() {
    const [isConnected, setIsConnected] = useState(socket.connected);

    useEffect(() => {
        socket.on('transaction', () => {
            console.log('connect')
            setIsConnected(true);
        });
        return () => {
            socket.off('transaction');
        };
    }, []);

    const sendPing = () => {
        socket.emit('ping');
    }

    return (
        <div>
            <p>Connected: { '' + isConnected }</p>
            <button onClick={ sendPing }>Send ping</button>
        </div>
    );
}

export default App;
