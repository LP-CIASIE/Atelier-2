class User {
  final String id;
  final String email;
  final String role;
  String accessToken;
  final String refreshToken;

  User({
    required this.id,
    required this.email,
    required this.role,
    required this.accessToken,
    required this.refreshToken,
  });
}
