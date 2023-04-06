import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'my_app.dart';
import 'provider/session_provider.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

void main() async {
  await dotenv.load();
  runApp(MultiProvider(providers: [
    ChangeNotifierProvider(create: (context) => SessionProvider()),
  ], child: const MyApp()));
}
