import 'package:flutter/material.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/sign_in_screen.dart';
import 'package:provider/provider.dart';

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
//   User user = User(accessToken: ' ', refreshToken: '');
//   Provider.of<Session
// // Provider.of<SessionProvider>(context, listen: false).set()

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home'),
      ),
      body: Column(children: [
        ElevatedButton(
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const SignInPage()),
            );
          },
          child: const Text("Se connecter"),
        ),
      ]),
    );
  }
}
